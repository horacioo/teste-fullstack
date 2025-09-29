<?php

declare(strict_types=1);

namespace MeuPlugin\Admin;

use MeuPlugin\CacheStore;

if (! class_exists('WP_List_Table')) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class ProductsTable extends \WP_List_Table
{
    private array $itemsData = [];

    public function __construct()
    {
        parent::__construct([
            'singular' => 'product',
            'plural'   => 'products',
            'ajax'     => false,
        ]);
    }

    public function get_columns(): array
    {
        return [
            'id'   => __('ID', 'meuplugin'),
            'name' => __('Nome', 'meuplugin'),
            'data' => __('Dados', 'meuplugin'),
        ];
    }

    public function prepare_items(): void
    {
        $store = new CacheStore(new \MeuPlugin\ApiClient());
        $products = $store->getProducts();
        $this->itemsData = array_map(static function (array $p): array {
            $data = $p['data'] ?? null;
            $short = '';
            $full = '';
            if ($data !== null) {
                $full = wp_json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                $short = mb_strlen($full) > 80 ? mb_substr($full, 0, 80) . '…' : $full;
            }
            return [
                'id' => (string) ($p['id'] ?? ''),
                'name' => (string) ($p['name'] ?? ''),
                'data' => [
                    'short' => $short,
                    'full' => $full,
                ],
            ];
        }, $products);

        $columns = $this->get_columns();
        $hidden = [];
        $sortable = [];
        $this->_column_headers = [$columns, $hidden, $sortable];
        $this->items = $this->itemsData;
    }

    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'id':
            case 'name':
                return esc_html((string) $item[$column_name]);
            case 'data':
                $short = isset($item['data']['short']) ? (string) $item['data']['short'] : '';
                $full = isset($item['data']['full']) ? (string) $item['data']['full'] : '';
                $title = $full !== '' ? esc_attr($full) : '';
                return '<span title="' . $title . '">' . esc_html($short) . '</span>';
            default:
                return '';
        }
    }
}


