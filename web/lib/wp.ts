export type Product = {
  id: string;
  name: string;
  data?: Record<string, unknown> | null;
};

const WP_BASE = process.env.NEXT_PUBLIC_WP_BASE_URL;

function getBase(): string {
  if (!WP_BASE) {
    throw new Error('NEXT_PUBLIC_WP_BASE_URL não definido');
  }
  return WP_BASE.replace(/\/$/, '');
}

export async function fetchProducts(): Promise<Product[]> {
  const res = await fetch(`${getBase()}/wp-json/meuplugin/v1/products`, {
    // CORS handled server-side (plugin) allowing GET; cache per ISR
    next: { revalidate: 600 },
  });
  if (!res.ok) {
    throw new Error('Falha ao buscar produtos');
  }
  const json = (await res.json()) as Product[];
  return json;
}

export async function fetchProduct(id: string): Promise<Product> {
  const res = await fetch(`${getBase()}/wp-json/meuplugin/v1/products/${encodeURIComponent(id)}`, {
    cache: 'no-store',
  });
  if (!res.ok) {
    if (res.status === 404) {
      throw new Error('Produto não encontrado');
    }
    throw new Error('Falha ao buscar produto');
  }
  const json = (await res.json()) as Product;
  return json;
}


