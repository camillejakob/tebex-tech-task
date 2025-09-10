<?php
return [
    'cache' => [
        // time to live in minutes
        'TTL' => 10
    ],
    'services' =>[
        'minecraft' => [
            'endpoint' => [
                'username' => 'https://api.mojang.com/users/profiles/minecraft/',
                'id' => 'https://sessionserver.mojang.com/session/minecraft/profile/'
            ],
            'cacheKey' => 'lookup:minecraft'
        ],
        'steam' => [
            'endpoint' => [
                'id' => 'https://ident.tebex.io/usernameservices/4/username/',
            ],
            'cacheKey' => 'lookup:steam',
        ],
        'xbl' => [
            'endpoint' => [
                'username' => 'https://ident.tebex.io/usernameservices/3/username/',
                'id' => 'https://ident.tebex.io/usernameservices/3/username/',
            ],
            'cacheKey' => 'lookup:xbl',
        ],
    ]
];
