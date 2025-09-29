export const metadata = {
  title: 'Produtos',
  description: 'Lista de produtos do WordPress via Meu Plugin',
};

export default function RootLayout({ children }: { children: React.ReactNode }) {
  return (
    <html lang="pt-BR">
      <body style={{ fontFamily: 'system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, Noto Sans, sans-serif', maxWidth: 960, margin: '0 auto', padding: 16 }}>
        <header style={{ padding: '16px 0' }}>
          <a href="/" style={{ textDecoration: 'none', color: 'inherit' }}>
            <h1 style={{ margin: 0 }}>Produtos</h1>
          </a>
        </header>
        <main>{children}</main>
      </body>
    </html>
  );
}


