<?php

declare(strict_types=1);

namespace MeuPlugin\Admin;

use MeuPlugin\Admin\ProductsTable;
use MeuPlugin\Admin\CachePage;

class Menu
{
    public static function register(): void
    {
        add_menu_page(
            __('Meu Plugin', 'meuplugin'),
            __('Meu Plugin', 'meuplugin'),
            'manage_options',
            'meuplugin',
            static function (): void {
                echo '<div class="wrap"><h1>' . esc_html__('Meu Plugin', 'meuplugin') . '</h1></div>';
            },
            'dashicons-admin-generic',
            65
        );

        add_submenu_page(
            'meuplugin',
            __('Produtos em Cache', 'meuplugin'),
            __('Produtos em Cache', 'meuplugin'),
            'manage_options',
            'meuplugin-products',
            [self::class, 'renderProducts']
        );

        add_submenu_page(
            'meuplugin',
            __('Cache', 'meuplugin'),
            __('Cache', 'meuplugin'),
            'manage_options',
            'meuplugin-cache',
            [CachePage::class, 'render']
        );
    }

    public static function renderProducts(): void
    {
        if (! current_user_can('manage_options')) {
            wp_die(__('Você não tem permissão para acessar esta página.', 'meuplugin'));
        }

        echo '<div class="wrap">';
        echo '<h1>' . esc_html__('Produtos em Cache', 'meuplugin') . '</h1>';

        $table = new ProductsTable();
        $table->prepare_items();
        echo '<form method="get">';
        // keep page parameters for list table nav working
        foreach (['page'] as $keep) {
            if (isset($_GET[$keep])) {
                echo '<input type="hidden" name="' . esc_attr($keep) . '" value="' . esc_attr((string) $_GET[$keep]) . '" />';
            }
        }
        $table->display();
        echo '</form>';
        echo '</div>';
    }
}


