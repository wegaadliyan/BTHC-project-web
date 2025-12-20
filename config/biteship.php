<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Biteship Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Biteship API integration.
    | Get your API key from https://biteship.com
    |
    */

    'api_key' => env('BITESHIP_API_KEY'),
    'base_url' => env('BITESHIP_BASE_URL', 'https://api.biteship.com/v1'),
    
    /*
    |--------------------------------------------------------------------------
    | Shipper Information (Your Business)
    |--------------------------------------------------------------------------
    |
    | Default shipper information used when creating orders
    |
    */
    'shipper' => [
        'contact_name' => env('BITESHIP_SHIPPER_NAME', 'BTHC Store'),
        'contact_phone' => env('BITESHIP_SHIPPER_PHONE', ''),
        'contact_email' => env('BITESHIP_SHIPPER_EMAIL', ''),
        'organization' => env('BITESHIP_SHIPPER_ORG', 'Better Hope Collection'),
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Origin (Pickup Location)
    |--------------------------------------------------------------------------
    |
    | Default pickup location for shipments
    |
    */
    'origin' => [
        'contact_name' => env('BITESHIP_ORIGIN_NAME', 'BTHC Store'),
        'contact_phone' => env('BITESHIP_ORIGIN_PHONE', ''),
        'contact_email' => env('BITESHIP_ORIGIN_EMAIL', ''),
        'address' => env('BITESHIP_ORIGIN_ADDRESS', ''),
        'postal_code' => env('BITESHIP_ORIGIN_POSTAL_CODE', ''),
        'note' => env('BITESHIP_ORIGIN_NOTE', ''),
        // For instant couriers, use coordinates instead of postal_code
        'latitude' => env('BITESHIP_ORIGIN_LATITUDE'),
        'longitude' => env('BITESHIP_ORIGIN_LONGITUDE'),
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Supported Couriers
    |--------------------------------------------------------------------------
    |
    | List of couriers Biteship supports for your region
    |
    */
    'couriers' => [
        'jne' => 'JNE',
        'tiki' => 'TIKI',
        'sicepat' => 'SiCepat',
        'pos' => 'Pos Indonesia',
        'grab' => 'Grab',
        'gojek' => 'Gojek',
        'deliveree' => 'Deliveree',
        'ninja' => 'Ninja Van',
        'anteraja' => 'Anteraja',
        'jnt' => 'J&T',
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Courier Types
    |--------------------------------------------------------------------------
    |
    | Default courier type for standard shipments
    |
    */
    'default_courier_type' => 'reg',
    
    /*
    |--------------------------------------------------------------------------
    | Delivery Settings
    |--------------------------------------------------------------------------
    |
    | Default delivery settings for orders
    |
    */
    'delivery' => [
        'type' => 'now', // 'now' or 'scheduled'
        'date' => null,  // Format: YYYY-MM-DD for scheduled
        'time' => null,  // Format: HH:mm for scheduled
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Insurance & COD Settings
    |--------------------------------------------------------------------------
    |
    | Default insurance and cash on delivery settings
    |
    */
    'insurance_enabled' => true,
    'cod_enabled' => false, // Set to true if using COD
    'cod_type' => '7_days', // '7_days', '5_days', or '3_days'
];
