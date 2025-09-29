# Meu Plugin (Cache de Produtos)

Plugin WordPress para cachear produtos vindos de API externa (`https://restful-api.dev/`) usando Transients. Inclui páginas de admin, endpoints REST e comando WP-CLI.

## Requisitos
- WordPress 6+
- PHP 8.0+

## Instalação
1. Copie a pasta `wp-plugin` para o diretório de plugins do WordPress (renomeie para `meu-plugin` se desejar).
2. Ative o plugin no painel (`Plugins`).

## Funcionalidades
- Cache via Transients:
  - `meuplugin_products_cache`: array de produtos
  - `meuplugin_products_cached_at`: timestamp ISO
  - TTL padrão: 10 min (constante `MEUPLUGIN_CACHE_TTL`)
- Admin (`Meu Plugin`):
  - `Produtos em Cache`: tabela com `id`, `name`, `data` (resumo + tooltip JSON)
  - `Cache`: exibe "Última atualização" e botão "Limpar cache"
- REST API (`meuplugin/v1`):
  - `GET /wp-json/meuplugin/v1/products`
  - `GET /wp-json/meuplugin/v1/products/{id}`
- WP-CLI:
  - `wp meuplugin cache clear`

## Segurança
- Acesso admin com `manage_options`.
- Nonce na ação de limpar cache.
- CORS permissivo apenas para GET dos endpoints do plugin.

## Desenvolvimento
- Padrões: PSR-12/WPCS.
- Estrutura de classes em `src/` com autoloader simples.

## Debug
- Em ambiente de desenvolvimento, consulte o log do PHP/WordPress. Evite expor detalhes no retorno REST.
