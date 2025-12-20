<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RajaOngkirController extends Controller
{
    /**
     * Get mock provinces data for testing
     * Use this as fallback when RajaOngkir API is not available
     */
    private function getMockProvinces()
    {
        return [
            ['province_id' => '1', 'province' => 'Bali'],
            ['province_id' => '2', 'province' => 'Bangka Belitung'],
            ['province_id' => '3', 'province' => 'Banten'],
            ['province_id' => '4', 'province' => 'Bengkulu'],
            ['province_id' => '5', 'province' => 'DI Yogyakarta'],
            ['province_id' => '6', 'province' => 'DKI Jakarta'],
            ['province_id' => '7', 'province' => 'Gorontalo'],
            ['province_id' => '8', 'province' => 'Jambi'],
            ['province_id' => '9', 'province' => 'Jawa Barat'],
            ['province_id' => '10', 'province' => 'Jawa Tengah'],
            ['province_id' => '11', 'province' => 'Jawa Timur'],
            ['province_id' => '12', 'province' => 'Kalimantan Barat'],
            ['province_id' => '13', 'province' => 'Kalimantan Selatan'],
            ['province_id' => '14', 'province' => 'Kalimantan Tengah'],
            ['province_id' => '15', 'province' => 'Kalimantan Timur'],
            ['province_id' => '16', 'province' => 'Kepulauan Riau'],
            ['province_id' => '17', 'province' => 'Lampung'],
            ['province_id' => '18', 'province' => 'Maluku'],
            ['province_id' => '19', 'province' => 'Maluku Utara'],
            ['province_id' => '20', 'province' => 'Nusa Tenggara Barat'],
            ['province_id' => '21', 'province' => 'Nusa Tenggara Timur'],
            ['province_id' => '22', 'province' => 'Papua'],
            ['province_id' => '23', 'province' => 'Papua Barat'],
            ['province_id' => '24', 'province' => 'Riau'],
            ['province_id' => '25', 'province' => 'Sulawesi Barat'],
            ['province_id' => '26', 'province' => 'Sulawesi Selatan'],
            ['province_id' => '27', 'province' => 'Sulawesi Tengah'],
            ['province_id' => '28', 'province' => 'Sulawesi Tenggara'],
            ['province_id' => '29', 'province' => 'Sulawesi Utara'],
            ['province_id' => '30', 'province' => 'Sumatera Barat'],
            ['province_id' => '31', 'province' => 'Sumatera Selatan'],
            ['province_id' => '32', 'province' => 'Sumatera Utara'],
        ];
    }

    /**
     * Get mock cities data for testing
     */
    private function getMockCities($province_id)
    {
        $mockCities = [
            '6' => [ // Jakarta
                ['city_id' => '154', 'province_id' => '6', 'city_name' => 'Jakarta Pusat', 'type' => 'Kota'],
                ['city_id' => '155', 'province_id' => '6', 'city_name' => 'Jakarta Selatan', 'type' => 'Kota'],
                ['city_id' => '156', 'province_id' => '6', 'city_name' => 'Jakarta Timur', 'type' => 'Kota'],
                ['city_id' => '157', 'province_id' => '6', 'city_name' => 'Jakarta Utara', 'type' => 'Kota'],
                ['city_id' => '158', 'province_id' => '6', 'city_name' => 'Jakarta Barat', 'type' => 'Kota'],
            ],
            '9' => [ // Jawa Barat
                ['city_id' => '73', 'province_id' => '9', 'city_name' => 'Bandung', 'type' => 'Kota'],
                ['city_id' => '71', 'province_id' => '9', 'city_name' => 'Bogor', 'type' => 'Kota'],
                ['city_id' => '72', 'province_id' => '9', 'city_name' => 'Bekasi', 'type' => 'Kota'],
            ],
            '10' => [ // Jawa Tengah
                ['city_id' => '168', 'province_id' => '10', 'city_name' => 'Semarang', 'type' => 'Kota'],
                ['city_id' => '165', 'province_id' => '10', 'city_name' => 'Solo', 'type' => 'Kota'],
            ],
        ];

        return $mockCities[$province_id] ?? [];
    }

    /**
     * Get mock shipping costs
     */
    private function getMockShippingCosts()
    {
        return [
            ['service' => 'Reguler', 'description' => 'Reguler', 'cost' => [['value' => 50000, 'etd' => '2-3', 'note' => '']]],
            ['service' => 'Express', 'description' => 'Express', 'cost' => [['value' => 75000, 'etd' => '1-2', 'note' => '']]],
        ];
    }

    public function getProvinces()
    {
        $apiKey = config('rajaongkir.api_key');
        $baseUrl = config('rajaongkir.base_url', 'https://api.rajaongkir.com/starter');
        
        // Try real API first
        if ($apiKey) {
            try {
                $response = Http::withHeaders([
                    'key' => $apiKey,
                ])->timeout(10)->get("$baseUrl/province");

                if ($response->successful()) {
                    return response()->json($response->json()['rajaongkir']['results']);
                }
            } catch (\Exception $e) {
                \Log::warning('RajaOngkir API Error: ' . $e->getMessage());
            }
        }

        // Return mock data as fallback
        return response()->json($this->getMockProvinces());
    }

    public function getCities($province_id)
    {
        $apiKey = config('rajaongkir.api_key');
        $baseUrl = config('rajaongkir.base_url', 'https://api.rajaongkir.com/starter');
        
        // Try real API first
        if ($apiKey) {
            try {
                $response = Http::withHeaders([
                    'key' => $apiKey,
                ])->timeout(10)->get("$baseUrl/city", [
                    'province' => $province_id
                ]);

                if ($response->successful()) {
                    return response()->json($response->json()['rajaongkir']['results']);
                }
            } catch (\Exception $e) {
                \Log::warning('RajaOngkir API Error: ' . $e->getMessage());
            }
        }

        // Return mock data as fallback
        return response()->json($this->getMockCities($province_id));
    }

    public function getDistricts($city_id)
    {
        // RajaOngkir Starter tidak support kecamatan, return array kosong
        return response()->json([]);
    }

    public function checkOngkir(Request $request)
    {
        $apiKey = config('rajaongkir.api_key');
        $baseUrl = config('rajaongkir.base_url', 'https://api.rajaongkir.com/starter');
        
        $request->validate([
            'origin' => 'nullable|numeric',
            'destination' => 'required|numeric',
            'weight' => 'required|numeric|min:1000',
            'courier' => 'required|string',
        ]);

        // Try real API first
        if ($apiKey) {
            try {
                $origin = $request->origin ?? env('RAJAONGKIR_ORIGIN_CITY_ID', 154);
                
                $response = Http::withHeaders([
                    'key' => $apiKey,
                ])->timeout(10)->post("$baseUrl/cost", [
                    'origin' => $origin,
                    'destination' => $request->destination,
                    'weight' => $request->weight,
                    'courier' => $request->courier,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data['rajaongkir']['results'][0]['costs'])) {
                        return response()->json($data['rajaongkir']['results'][0]['costs']);
                    }
                }
            } catch (\Exception $e) {
                \Log::warning('RajaOngkir API Error: ' . $e->getMessage());
            }
        }

        // Return mock data as fallback
        return response()->json($this->getMockShippingCosts());
    }
