<?php

return [
    // Midtrans server key (secret) - keep in .env
    'server_key' => env('MIDTRANS_SERVER_KEY', null),

    // Midtrans client key (public) - safe to expose to frontend
    'client_key' => env('MIDTRANS_CLIENT_KEY', null),

    // Use production endpoints when true. Use FILTER_VALIDATE_BOOLEAN so string values
    // like 'false' or '0' in the .env are parsed correctly as boolean false.
    'production' => filter_var(env('MIDTRANS_PRODUCTION', false), FILTER_VALIDATE_BOOLEAN),
    
    // Merchant ID from Midtrans Dashboard
    'merchant_id' => env('MIDTRANS_MERCHANT_ID', null),
];
