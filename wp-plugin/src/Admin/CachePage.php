<?php

declare(strict_types=1);

namespace MeuPlugin\Admin;

use MeuPlugin\CacheStore;

class CachePage
{
    public static function render(): void
    {
        if (! current_user_can('manage_options')) {
            wp_die(__('Você não tem permissão para acessar esta página.', 'meuplugin'));
        }

        $store = new CacheStore(new \MeuPlugin\ApiClient());

        // Handle clear action
        if (isset($_POST['meuplugin_clear_cache'])) {
            check_admin_referer('meuplugin_clear_cache_action', 'meuplugin_clear_cache_nonce');
            $store->clear();
            add_settings_error('meuplugin', 'cache_cleared', __('Cache limpo com sucesso.', 'meuplugin'), 'updated');
        }

        settings_errors('meuplugin');

        $cachedAt = $store->getCachedAt();
        echo '<div class="wrap">';
        echo '<h1>' . esc_html__('Cache', 'meuplugin') . '</h1>';
        echo '<p>' . esc_html__('Última atualização:', 'meuplugin') . ' ' . esc_html($cachedAt ?? __('sem dados', 'meuplugin')) . '</p>';

        echo '<form method="post">';
        wp_nonce_field('meuplugin_clear_cache_action', 'meuplugin_clear_cache_nonce');
        echo '<input type="hidden" name="meuplugin_clear_cache" value="1" />';
        submit_button(__('Limpar cache', 'meuplugin'));
        echo '</form>';

        echo '</div>';
    }
}


