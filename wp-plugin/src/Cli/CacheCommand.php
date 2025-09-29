<?php

declare(strict_types=1);

namespace MeuPlugin\Cli;

use MeuPlugin\CacheStore;

class CacheCommand
{
    public static function register(): void
    {
        // Register nested command: wp meuplugin cache clear
        \WP_CLI::add_command('meuplugin cache', self::class);
    }

    /**
     * Clear products cache.
     *
     * ## EXAMPLES
     *
     *     wp meuplugin cache clear
     */
    public function clear(): void
    {
        $store = new CacheStore(new \MeuPlugin\ApiClient());
        $store->clear();
        $now = (new \DateTimeImmutable('now', new \DateTimeZone('UTC')))->format('c');
        \WP_CLI::success('Cache cleared at ' . $now);
    }
}


