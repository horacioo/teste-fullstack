import Link from 'next/link';
import { fetchProducts } from '../lib/wp';

export const revalidate = 600; // ISR para a home

export default async function Page() {
  let products = [] as Awaited<ReturnType<typeof fetchProducts>>;
  try {
    products = await fetchProducts();
  } catch (e) {
    return (
      <div role="alert" aria-live="polite" style={{ color: 'crimson' }}>
        Ocorreu um erro ao carregar os produtos. Tente novamente mais tarde.
      </div>
    );
  }

  if (!products?.length) {
    return <p>Nenhum produto encontrado.</p>;
  }

  return (
    <ul style={{ display: 'grid', gap: 12, padding: 0, listStyle: 'none' }}>
      {products.map((p) => (
        <li key={p.id} style={{ border: '1px solid #ddd', borderRadius: 8, padding: 12 }}>
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
            <div>
              <div style={{ fontWeight: 600 }}>{p.name}</div>
              <div style={{ color: '#666', fontSize: 12 }}>ID: {p.id}</div>
            </div>
            <Link href={`/products/${p.id}`}>Detalhes</Link>
          </div>
        </li>
      ))}
    </ul>
  );
}


