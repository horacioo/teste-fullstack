<?php

declare(strict_types=1);

namespace MeuPlugin\Rest;

class Routes
{
    public static function register(): void
    {
        register_rest_route(MEUPLUGIN_NS, '/products', [
            [
                'methods'  => 'GET',
                'callback' => [ProductsController::class, 'index'],
                'permission_callback' => '__return_true',
            ],
        ]);

        register_rest_route(MEUPLUGIN_NS, '/products/(?P<id>[^/]+)', [
            [
                'methods'  => 'GET',
                'callback' => [ProductsController::class, 'show'],
                'args' => [
                    'id' => [
                        'required' => true,
                        'type' => 'string',
                    ],
                ],
                'permission_callback' => '__return_true',
            ],
        ]);
    }
}


