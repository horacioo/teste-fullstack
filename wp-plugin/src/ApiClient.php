<?php

declare(strict_types=1);

namespace MeuPlugin;

class ApiClient
{
    private const BASE_URL = 'https://api.restful-api.dev';

    /**
     * Fetch all products (objects) from external API.
     *
     * @return array<int, array<string, mixed>>
     */
    public function fetchAllProducts(): array
    {
        $url = self::BASE_URL . '/objects';
        $response = wp_remote_get($url, [
            'timeout' => 10,
        ]);

        if (is_wp_error($response)) {
            return [];
        }

        $code = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);
        if ($code !== 200 || empty($body)) {
            return [];
        }

        $data = json_decode($body, true);
        if (! is_array($data)) {
            return [];
        }

        // Normalize to expected structure (id, name, data?) as-is
        return array_values(array_filter(array_map(static function ($item) {
            if (! is_array($item)) {
                return null;
            }
            $id = $item['id'] ?? null;
            $name = $item['name'] ?? null;
            if (! is_string($id) || $id === '' || ! is_string($name) || $name === '') {
                return null;
            }
            return [
                'id' => $id,
                'name' => $name,
                'data' => $item['data'] ?? null,
            ];
        }, $data)));
    }

    /**
     * Fetch a single product by id.
     *
     * @param string $id
     * @return array<string, mixed>|null
     */
    public function fetchProductById(string $id): ?array
    {
        $id = trim($id);
        if ($id === '') {
            return null;
        }

        $url = self::BASE_URL . '/objects/' . rawurlencode($id);
        $response = wp_remote_get($url, [
            'timeout' => 10,
        ]);

        if (is_wp_error($response)) {
            return null;
        }

        $code = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);
        if ($code !== 200 || empty($body)) {
            return null;
        }

        $item = json_decode($body, true);
        if (! is_array($item)) {
            return null;
        }

        $pid = $item['id'] ?? null;
        $name = $item['name'] ?? null;
        if (! is_string($pid) || $pid === '' || ! is_string($name) || $name === '') {
            return null;
        }

        return [
            'id' => $pid,
            'name' => $name,
            'data' => $item['data'] ?? null,
        ];
    }
}


