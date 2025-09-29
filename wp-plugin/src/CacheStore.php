<?php

declare(strict_types=1);

namespace MeuPlugin;

class CacheStore
{
    public const PRODUCTS_KEY = 'meuplugin_products_cache';
    public const CACHED_AT_KEY = 'meuplugin_products_cached_at';

    public function __construct(private ApiClient $apiClient)
    {
    }

    /**
     * Get products from cache, repopulating if missing/expired.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getProducts(): array
    {
        $products = get_transient(self::PRODUCTS_KEY);
        if ($products === false || ! is_array($products)) {
            $products = $this->apiClient->fetchAllProducts();
            set_transient(self::PRODUCTS_KEY, $products, (int) MEUPLUGIN_CACHE_TTL);
            set_transient(self::CACHED_AT_KEY, $this->nowIso8601(), (int) MEUPLUGIN_CACHE_TTL);
        }
        return $products;
    }

    /**
     * Get single product by id from cache, repopulating if needed.
     */
    public function getProductById(string $id): ?array
    {
        $id = trim($id);
        if ($id === '') {
            return null;
        }
        $products = $this->getProducts();
        foreach ($products as $product) {
            if (is_array($product) && ($product['id'] ?? null) === $id) {
                return $product;
            }
        }
        // Not found in cache, try refetch once
        $this->refresh();
        $products = get_transient(self::PRODUCTS_KEY);
        if (is_array($products)) {
            foreach ($products as $product) {
                if (is_array($product) && ($product['id'] ?? null) === $id) {
                    return $product;
                }
            }
        }
        return null;
    }

    /**
     * Force refresh from API.
     */
    public function refresh(): void
    {
        $products = $this->apiClient->fetchAllProducts();
        set_transient(self::PRODUCTS_KEY, $products, (int) MEUPLUGIN_CACHE_TTL);
        set_transient(self::CACHED_AT_KEY, $this->nowIso8601(), (int) MEUPLUGIN_CACHE_TTL);
    }

    /**
     * Clear cache.
     */
    public function clear(): void
    {
        delete_transient(self::PRODUCTS_KEY);
        delete_transient(self::CACHED_AT_KEY);
    }

    /**
     * Get ISO timestamp when cache was last written.
     */
    public function getCachedAt(): ?string
    {
        $ts = get_transient(self::CACHED_AT_KEY);
        return is_string($ts) && $ts !== '' ? $ts : null;
    }

    private function nowIso8601(): string
    {
        $dt = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
        return $dt->format('c');
    }
}


