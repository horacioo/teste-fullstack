<?php

declare(strict_types=1);

namespace MeuPlugin\Rest;

use MeuPlugin\CacheStore;

class ProductsController
{
    public static function index(\WP_REST_Request $request): \WP_REST_Response
    {
        $store = new CacheStore(new \MeuPlugin\ApiClient());
        $products = $store->getProducts();
        return new \WP_REST_Response($products, 200);
    }

    public static function show(\WP_REST_Request $request): \WP_REST_Response
    {
        $id = (string) $request->get_param('id');
        $store = new CacheStore(new \MeuPlugin\ApiClient());
        $product = $store->getProductById($id);
        if ($product === null) {
            return new \WP_REST_Response([
                'code' => 'not_found',
                'message' => __('Produto não encontrado.', 'meuplugin'),
            ], 404);
        }
        return new \WP_REST_Response($product, 200);
    }
}


