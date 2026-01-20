<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BiteshipController extends Controller
{
    private $apiKey;
    private $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('biteship.api_key');
        $this->baseUrl = config('biteship.base_url', 'https://api.biteship.com/v1');
    }

    /**
     * Get list of available couriers
     * GET /couriers
     */
    public function getCouriers()
    {
        try {
            \Log::info('getCouriers: Start');
            
            $apiKey = config('biteship.api_key');
            \Log::info('API Key configured: ' . (!empty($apiKey) ? 'yes' : 'no'));
            
            if (!$apiKey) {
                \Log::warning('API Key is not configured');
                return response()->json(['error' => 'API Key tidak dikonfigurasi'], 500);
            }

            $baseUrl = config('biteship.base_url', 'https://api.biteship.com/v1');
            \Log::info('Making request to: ' . $baseUrl . '/couriers');
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
            ])->timeout(10)->get("{$baseUrl}/couriers");

            \Log::info('Response status: ' . $response->status());
            
            if ($response->successful()) {
                $allCouriers = $response->json()['couriers'] ?? [];
                \Log::info('Total couriers received: ' . count($allCouriers));
                
                // Log all courier codes for debugging
                $allCodes = array_map(function($c) { return $c['courier_code'] ?? 'unknown'; }, $allCouriers);
                \Log::info('All courier codes: ' . implode(', ', array_unique($allCodes)));
                
                // Kurir yang ingin dihapus
                $excludedCouriers = [
                    'gojek',
                    'grab',
                    'deliveree',
                    'ninja',
                    'lion',
                    'sentralcargo',
                    'sentral_cargo',
                    'sentral-cargo',
                    'scentralcargo',
                    'centralcargo',
                    'idexpress',
                    'rpx',
                    'wahana',
                    'anteraja',
                    'sap',
                    'borzo',
                    'lalamove',
                    'dash',
                    'dash_express',
                    'dashexpress',
                    'pos',
                    'paxel',
                ];
                
                // Group couriers by courier_code and get first instance of each
                $groupedCouriers = [];
                $seenCodes = [];
                
                foreach ($allCouriers as $courier) {
                    $code = $courier['courier_code'] ?? null;
                    
                    // Skip if already seen
                    if (!$code || in_array($code, $seenCodes)) {
                        continue;
                    }
                    
                    // Skip if courier is in excluded list (check with multiple variations)
                    if (in_array(strtolower($code), $excludedCouriers) || in_array(str_replace('-', '_', strtolower($code)), $excludedCouriers)) {
                        continue;
                    }
                    
                    // Add to grouped couriers
                    $groupedCouriers[] = [
                        'courier_code' => $courier['courier_code'],
                        'courier_name' => $courier['courier_name'] ?? '',
                        'courier_service_name' => $courier['courier_service_name'] ?? '',
                        'courier_service_code' => $courier['courier_service_code'] ?? '',
                        'shipment_duration_range' => $courier['shipment_duration_range'] ?? '',
                        'shipment_duration_unit' => $courier['shipment_duration_unit'] ?? 'days',
                        'description' => $courier['description'] ?? '',
                    ];
                    
                    $seenCodes[] = $code;
                }

                \Log::info('Returning ' . count($groupedCouriers) . ' grouped couriers');
                return response()->json($groupedCouriers);
            }

            \Log::error('Biteship API Error', ['status' => $response->status(), 'body' => $response->body()]);
            return response()->json(['error' => 'Gagal mengambil data kurir: ' . $response->status()], 500);
        } catch (\Exception $e) {
            \Log::error('Biteship Exception: ' . $e->getMessage() . '\n' . $e->getTraceAsString());
            return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get rates/quotes for shipping
     * POST /rates (with origin and destination details)
     */
    public function getRates(Request $request)
    {
        try {
            \Log::info('getRates: Start', $request->all());
            
            $apiKey = config('biteship.api_key');
            if (!$apiKey) {
                \Log::warning('API Key not configured for getRates');
                return response()->json(['error' => 'API Key tidak dikonfigurasi'], 500);
            }

            $request->validate([
                'destination_postal_code' => 'required|numeric',
                'weight' => 'required|numeric|min:100',
                'courier_code' => 'required|string',
            ]);

            $originPostalCode = config('biteship.origin.postal_code', '12440');
            \Log::info('Origin postal code: ' . $originPostalCode);
            \Log::info('Destination: ' . $request->destination_postal_code);
            \Log::info('Weight: ' . $request->weight);
            \Log::info('Courier: ' . $request->courier_code);

            $payload = [
                'origin_postal_code' => $originPostalCode,
                'destination_postal_code' => (string) $request->destination_postal_code,
                'weight' => (int) $request->weight,
                'courier_code' => $request->courier_code,
                'items' => [
                    [
                        'name' => 'Package',
                        'description' => 'Customer order',
                        'weight' => (int) $request->weight,
                        'value' => (int) ($request->item_value ?? 0),
                        'quantity' => 1,
                    ]
                ]
            ];

            \Log::info('Sending payload to Biteship', $payload);

            $baseUrl = config('biteship.base_url', 'https://api.biteship.com/v1');
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(10)->post("{$baseUrl}/rates", $payload);

            \Log::info('Biteship response status: ' . $response->status());

            if ($response->successful()) {
                $rates = $response->json()['rates'] ?? [];
                \Log::info('Rates returned: ' . count($rates));
                return response()->json($rates);
            }

            $errorBody = $response->json();
            $errorMessage = $errorBody['error'] ?? json_encode($errorBody);
            
            \Log::error('Biteship Rates API Error', [
                'status' => $response->status(),
                'body' => json_encode($errorBody),
                'message' => $errorMessage
            ]);
            
            // If in testing mode and any error occurs (especially route not found), return mock rates for development
            if (!config('biteship.is_production', false)) {
                \Log::info('Testing mode: Returning mock rates instead of error');
                return response()->json($this->getMockRates($request->courier_code));
            }
            
            return response()->json(['error' => $errorMessage], 400);
        } catch (\Exception $e) {
            \Log::error('Biteship getRates Exception: ' . $e->getMessage() . ' ' . $e->getTraceAsString());
            return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Create a shipping order
     * POST /orders
     */
    public function createOrder(Request $request)
    {
        if (!$this->apiKey) {
            return response()->json(['error' => 'API Key tidak dikonfigurasi'], 500);
        }

        $request->validate([
            'destination_contact_name' => 'required|string',
            'destination_contact_phone' => 'required|string',
            'destination_contact_email' => 'nullable|email',
            'destination_address' => 'required|string',
            'destination_postal_code' => 'required|numeric',
            'destination_note' => 'nullable|string',
            'courier_company' => 'required|string',
            'courier_type' => 'required|string',
            'weight' => 'required|numeric|min:100',
            'items' => 'required|array',
            'reference_id' => 'nullable|string|unique:orders,reference_id',
        ]);

        try {
            $shipper = config('biteship.shipper');
            $origin = config('biteship.origin');

            $payload = [
                'shipper_contact_name' => $shipper['contact_name'],
                'shipper_contact_phone' => $shipper['contact_phone'],
                'shipper_contact_email' => $shipper['contact_email'],
                'shipper_organization' => $shipper['organization'],
                'origin_contact_name' => $origin['contact_name'],
                'origin_contact_phone' => $origin['contact_phone'],
                'origin_address' => $origin['address'],
                'origin_postal_code' => $origin['postal_code'],
                'origin_note' => $origin['note'],
                'destination_contact_name' => $request->destination_contact_name,
                'destination_contact_phone' => $request->destination_contact_phone,
                'destination_contact_email' => $request->destination_contact_email,
                'destination_address' => $request->destination_address,
                'destination_postal_code' => $request->destination_postal_code,
                'destination_note' => $request->destination_note,
                'courier_company' => $request->courier_company,
                'courier_type' => $request->courier_type,
                'delivery_type' => config('biteship.delivery.type', 'now'),
                'items' => $request->items,
                'reference_id' => $request->reference_id,
            ];

            // Add delivery date/time if scheduled
            if (config('biteship.delivery.type') === 'scheduled') {
                $payload['delivery_date'] = config('biteship.delivery.date');
                $payload['delivery_time'] = config('biteship.delivery.time');
            }

            // Add insurance if enabled
            if (config('biteship.insurance_enabled')) {
                $totalValue = collect($request->items)->sum('value');
                if ($totalValue > 0) {
                    $payload['courier_insurance'] = $totalValue;
                }
            }

            // Add COD if enabled
            if (config('biteship.cod_enabled')) {
                $payload['destination_cash_on_delivery'] = $request->cod_amount ?? 0;
                $payload['destination_cash_on_delivery_type'] = config('biteship.cod_type', '7_days');
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->timeout(15)->post("{$this->baseUrl}/orders", $payload);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            // Handle specific error codes
            if ($response->status() === 400) {
                $error = $response->json()['error'] ?? 'Invalid request';
                Log::error('Biteship Order Error', ['error' => $error, 'response' => $response->json()]);
                return response()->json(['error' => $error], 400);
            }

            Log::error('Biteship Order API Error', ['status' => $response->status(), 'body' => $response->body()]);
            return response()->json(['error' => 'Gagal membuat order pengiriman'], 500);
        } catch (\Exception $e) {
            Log::error('Biteship Order Exception: ' . $e->getMessage());
            return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get order details and tracking
     * GET /orders/:id
     */
    public function getOrder($orderId)
    {
        if (!$this->apiKey) {
            return response()->json(['error' => 'API Key tidak dikonfigurasi'], 500);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->timeout(10)->get("{$this->baseUrl}/orders/{$orderId}");

            if ($response->successful()) {
                return response()->json($response->json());
            }

            Log::error('Biteship Get Order Error', ['status' => $response->status(), 'body' => $response->body()]);
            return response()->json(['error' => 'Order tidak ditemukan'], 404);
        } catch (\Exception $e) {
            Log::error('Biteship Get Order Exception: ' . $e->getMessage());
            return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get tracking information
     * GET /trackings/:id
     */
    public function getTracking($trackingId)
    {
        if (!$this->apiKey) {
            return response()->json(['error' => 'API Key tidak dikonfigurasi'], 500);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->timeout(10)->get("{$this->baseUrl}/trackings/{$trackingId}");

            if ($response->successful()) {
                return response()->json($response->json());
            }

            Log::error('Biteship Tracking Error', ['status' => $response->status(), 'body' => $response->body()]);
            return response()->json(['error' => 'Tracking tidak ditemukan'], 404);
        } catch (\Exception $e) {
            Log::error('Biteship Tracking Exception: ' . $e->getMessage());
            return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get public tracking (no auth needed)
     * GET /trackings/:waybill_id/couriers/:courier_code
     */
    public function getPublicTracking($waybillId, $courierCode)
    {
        try {
            $response = Http::timeout(10)->get(
                "{$this->baseUrl}/trackings/{$waybillId}/couriers/{$courierCode}"
            );

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json(['error' => 'Tracking tidak ditemukan'], 404);
        } catch (\Exception $e) {
            Log::error('Biteship Public Tracking Exception: ' . $e->getMessage());
            return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Cancel an order
     * POST /orders/:id/cancel
     */
    public function cancelOrder($orderId, Request $request)
    {
        if (!$this->apiKey) {
            return response()->json(['error' => 'API Key tidak dikonfigurasi'], 500);
        }

        try {
            $payload = [
                'cancellation_reason_code' => $request->reason_code ?? 'others',
            ];

            if ($request->reason_code === 'others' && $request->reason) {
                $payload['cancellation_reason'] = $request->reason;
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->timeout(10)->post("{$this->baseUrl}/orders/{$orderId}/cancel", $payload);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            Log::error('Biteship Cancel Order Error', ['status' => $response->status(), 'body' => $response->body()]);
            return response()->json(['error' => 'Gagal membatalkan order'], 500);
        } catch (\Exception $e) {
            Log::error('Biteship Cancel Order Exception: ' . $e->getMessage());
            return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get cancellation reasons
     * GET /orders/cancellation_reasons
     */
    public function getCancellationReasons($lang = 'en')
    {
        if (!$this->apiKey) {
            return response()->json(['error' => 'API Key tidak dikonfigurasi'], 500);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->timeout(10)->get("{$this->baseUrl}/orders/cancellation_reasons", [
                'lang' => $lang
            ]);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json(['error' => 'Gagal mengambil alasan pembatalan'], 500);
        } catch (\Exception $e) {
            Log::error('Biteship Cancellation Reasons Exception: ' . $e->getMessage());
            return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get mock rates for development/testing when Biteship API is unavailable
     * This is used in testing mode when routes are not available in Biteship sandbox
     */
    private function getMockRates($courierCode = null)
    {
        $mockRates = [
            [
                'courier_company' => 'jne',
                'courier_code' => 'jne',
                'courier_name' => 'JNE',
                'courier_service_name' => 'Reguler',
                'courier_service_code' => 'reg',
                'shipment_duration_range' => '2-3',
                'shipment_duration_unit' => 'days',
                'weight' => 1000,
                'price' => 25000,
                'description' => 'Pengiriman reguler',
                'tier' => 'free',
            ],
            [
                'courier_company' => 'jne',
                'courier_code' => 'jne',
                'courier_name' => 'JNE',
                'courier_service_name' => 'YES',
                'courier_service_code' => 'yes',
                'shipment_duration_range' => '1',
                'shipment_duration_unit' => 'days',
                'weight' => 1000,
                'price' => 45000,
                'description' => 'Express, next day',
                'tier' => 'essentials',
            ],
            [
                'courier_company' => 'tiki',
                'courier_code' => 'tiki',
                'courier_name' => 'TIKI',
                'courier_service_name' => 'REG',
                'courier_service_code' => 'reg',
                'shipment_duration_range' => '2-3',
                'shipment_duration_unit' => 'days',
                'weight' => 1000,
                'price' => 28000,
                'description' => 'Layanan reguler',
                'tier' => 'free',
            ],
            [
                'courier_company' => 'tiki',
                'courier_code' => 'tiki',
                'courier_name' => 'TIKI',
                'courier_service_name' => 'SDS',
                'courier_service_code' => 'sds',
                'shipment_duration_range' => '1',
                'shipment_duration_unit' => 'days',
                'weight' => 1000,
                'price' => 52000,
                'description' => 'Same day service',
                'tier' => 'premium',
            ],
            [
                'courier_company' => 'jnt',
                'courier_code' => 'jnt',
                'courier_name' => 'J&T',
                'courier_service_name' => 'EZ',
                'courier_service_code' => 'ez',
                'shipment_duration_range' => '2-3',
                'shipment_duration_unit' => 'days',
                'weight' => 1000,
                'price' => 22000,
                'description' => 'Layanan reguler',
                'tier' => 'free',
            ],
            [
                'courier_company' => 'sicepat',
                'courier_code' => 'sicepat',
                'courier_name' => 'SiCepat',
                'courier_service_name' => 'Reguler',
                'courier_service_code' => 'reg',
                'shipment_duration_range' => '2-3',
                'shipment_duration_unit' => 'days',
                'weight' => 1000,
                'price' => 32000,
                'description' => 'Layanan reguler',
                'tier' => 'free',
            ],
            [
                'courier_company' => 'sicepat',
                'courier_code' => 'sicepat',
                'courier_name' => 'SiCepat',
                'courier_service_name' => 'Besok Sampai Tujuan',
                'courier_service_code' => 'best',
                'shipment_duration_range' => '1',
                'shipment_duration_unit' => 'days',
                'weight' => 1000,
                'price' => 48000,
                'description' => 'Besok sampai tujuan',
                'tier' => 'essentials',
            ],
        ];

        // Always return all mock rates (development mode doesn't filter)
        return $mockRates;
    }
}
