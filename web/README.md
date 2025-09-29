# Web (Next.js)

App Next.js 14+ consumindo o WordPress (`Meu Plugin`) via REST.

## Variáveis
Crie `.env.local`:

```
NEXT_PUBLIC_WP_BASE_URL=http://localhost:8080
```

## Rodar
```
pnpm i
pnpm dev
```
Acesse `http://localhost:3000`.

## Estratégias de renderização
- Home (`/`): ISR com `revalidate: 600` para equilibrar frescor e performance, pois a lista tolera leve defasagem dentro do TTL do cache no WP.
- Detalhe (`/products/[id]`): SSR (`dynamic = 'force-dynamic'`) para refletir o estado do cache do WordPress em tempo real e retornar 404 imediatamente quando não existir.

## Critérios de aceite
- `/` lista `id` e `name` de produtos.
- `/products/[id]` mostra detalhes (`data`) renderizados de forma legível.
- Sem erros de CORS em dev (plugin habilita GET permissivo para seu namespace).
