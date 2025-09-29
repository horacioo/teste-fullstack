import { fetchProduct } from '../../../lib/wp';

type Props = {
  params: { id: string };
};

export const dynamic = 'force-dynamic'; // SSR para refletir cache em tempo real

export default async function ProductPage({ params }: Props) {
  const { id } = params;
  try {
    const product = await fetchProduct(id);
    const entries = product.data && typeof product.data === 'object' ? Object.entries(product.data) : [];
    return (
      <div style={{ display: 'grid', gap: 16 }}>
        <div>
          <h2 style={{ margin: 0 }}>{product.name}</h2>
          <div style={{ color: '#666' }}>ID: {product.id}</div>
        </div>
        {entries.length ? (
          <table style={{ borderCollapse: 'collapse', width: '100%' }}>
            <thead>
              <tr>
                <th style={{ textAlign: 'left', borderBottom: '1px solid #ddd', padding: 8 }}>Campo</th>
                <th style={{ textAlign: 'left', borderBottom: '1px solid #ddd', padding: 8 }}>Valor</th>
              </tr>
            </thead>
            <tbody>
              {entries.map(([k, v]) => (
                <tr key={k}>
                  <td style={{ borderBottom: '1px solid #f0f0f0', padding: 8 }}>{k}</td>
                  <td style={{ borderBottom: '1px solid #f0f0f0', padding: 8 }}>
                    {typeof v === 'object' ? <pre style={{ margin: 0 }}>{JSON.stringify(v, null, 2)}</pre> : String(v)}
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        ) : (
          <p>Sem dados adicionais.</p>
        )}
      </div>
    );
  } catch (e: unknown) {
    const message = e instanceof Error ? e.message : 'Erro desconhecido';
    return (
      <div role="alert" aria-live="polite" style={{ color: 'crimson' }}>
        {message}
      </div>
    );
  }
}


