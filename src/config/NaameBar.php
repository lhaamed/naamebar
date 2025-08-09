<?php



return [


    /*
    |--------------------------------------------------------------------------
    | NaameBar Configuration
    |--------------------------------------------------------------------------
    |
    | there are 2 main configuration for From and List bundles. So you can determine configs individually in each.
    */

    'panels' => [
        'sms-ir' =>[
            'username' => 'lhaamed',
            'api_key' => 'mrgBubWtXwGihnFjsga1Ve3vpDZVFfRj0v9XGo3Mgik86HM6dAelKNTCRf7u0hXh',
            'lines' => [
                '30007732002350'
            ],
            'default_line' => '30007732002350',
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
