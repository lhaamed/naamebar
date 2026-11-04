<?php



return [


    /*
    |--------------------------------------------------------------------------
    | NaameBar Configuration
    |--------------------------------------------------------------------------
    |
    | SMS.ir credentials and endpoints. Configure credentials in your application environment.
    */

    'panels' => [
        'sms-ir' =>[
            'username' => env('SMSIR_USERNAME', ''),
            'api_key' => env('SMSIR_API_KEY', ''),
            'lines' => [],
            'default_line' => env('SMSIR_LINE_NUMBER', ''),
            'template_id' => env('SMSIR_TEMPLATE_ID'),
            'api_routes' => [
                'normal-dispatcher' => [
                    'methods' => ['POST','GET'],
                    'route' => 'https://api.sms.ir/v1/send',
                ],
                'bulk-dispatcher' => [
                    'methods' => ['POST'],
                    'route' => 'https://api.sms.ir/v1/send/bulk',
                ],
                'verify-dispatcher' => [
                    'methods' => ['POST'],
                    'route' => 'https://api.sms.ir/v1/send/verify',
                ],
                'p2p-dispatcher' => [
                    'methods' => ['POST'],
                    'route' => 'https://api.sms.ir/v1/send/likeToLike',
                ],
                'credit' => [
                    'methods' => ['GET'],
                    'route' => 'https://api.sms.ir/v1/credit',
                ],
                'lines' => [
                    'methods' => ['GET'],
                    'route' => 'https://api.sms.ir/v1/line',
                ],
                'single-inquiry' => [
                    'methods' => ['GET'],
                    'route' => 'https://api.sms.ir/v1/send',
                ]
            ]
        ]
    ]


];
