<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mercadex</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

  @auth
  <nav class="main-nav">

    {{-- Brand --}}
    <a class="nav-brand" href="{{ route('home') }}">
      <img src="{{ asset('images/logo.png') }}" alt="Mercadex" class="nav-brand-logo">
      <span class="nav-brand-name">Mercadex</span>
    </a>

    {{-- Mobile toggle --}}
    <button class="nav-toggle" onclick="this.nextElementSibling.nextElementSibling.classList.toggle('open')">☰</button>

    {{-- Links por rol --}}
    <ul class="nav-links">
      @if(Auth::user()->role === 'admin')
        <li class="nav-item">
          <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            Dashboard
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('productos.index') }}" class="{{ request()->routeIs('productos.*') ? 'active' : '' }}">
            Productos
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('ventas.index') }}" class="{{ request()->routeIs('ventas.*') ? 'active' : '' }}">
            Ventas
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('admin.usuarios') }}" class="{{ request()->routeIs('admin.usuarios*') ? 'active' : '' }}">
            Usuarios
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('proveedores.index') }}" class="{{ request()->routeIs('proveedores.*') ? 'active' : '' }}">
            Proveedores
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('categorias.index') }}" class="{{ request()->routeIs('categorias.*') ? 'active' : '' }}">
            Categorías
          </a>
        </li>
      @endif

      @if(Auth::user()->role === 'vendedor')
        <li class="nav-item">
          <a href="{{ route('vendedor.dashboard') }}" class="{{ request()->routeIs('vendedor.dashboard') ? 'active' : '' }}">
            Dashboard
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('ventas.create') }}" class="{{ request()->routeIs('ventas.create') ? 'active' : '' }}">
            Nueva venta
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('ventas.index') }}" class="{{ request()->routeIs('ventas.index') ? 'active' : '' }}">
            Mis ventas
          </a>
        </li>
      @endif
    </ul>

    {{-- Usuario + logout --}}
    <div class="nav-user">
      <div class="nav-user-info">
        <span class="nav-user-name">{{ Auth::user()->name }}</span>
        <span class="role-badge {{ Auth::user()->role }}">{{ Auth::user()->role }}</span>
      </div>
      <div class="nav-avatar">
        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
      </div>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="btn-logout" type="submit">⏏ Salir</button>
      </form>
    </div>

  </nav>
  @endauth

  <div class="page-content">
    @yield('content')
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    // ── Confirmaciones de eliminación ──────────────────
    document.querySelectorAll('.form-delete').forEach(form => {
      form.addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
          title: '¿Estás seguro?',
          text: 'Esta acción no se puede deshacer.',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Sí, eliminar',
          cancelButtonText: 'Cancelar',
          confirmButtonColor: '#ef4444',
          cancelButtonColor: '#6b7280',
          background: '#1a1200',
          color: '#fff',
          iconColor: '#f97316',
        }).then((result) => {
          if (result.isConfirmed) {
            form.submit();
          }
        });
      });
    });

    // ── Mensajes de sesión ──────────────────────────────
    const successMsg = "{{ session('success') }}";
    const errorMsg   = "{{ session('error') }}";

    if (successMsg) {
      Swal.fire({
        icon: 'success',
        title: '¡Listo!',
        text: successMsg,
        timer: 2500,
        timerProgressBar: true,
        showConfirmButton: false,
        background: '#1a1200',
        color: '#fff',
        iconColor: '#22c55e',
      });
    }

    if (errorMsg) {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: errorMsg,
        background: '#1a1200',
        color: '#fff',
        iconColor: '#ef4444',
        confirmButtonColor: '#f97316',
      });
    }
  </script>

  {{-- Alertas de stock bajo solo para admin --}}
  @auth
  @if(Auth::user()->role === 'admin')
  @php
    $sinStock  = \App\Models\Producto::where('activo', true)->where('stock', 0)->get();
    $stockBajo = \App\Models\Producto::where('activo', true)->where('stock', '<=', 5)->where('stock', '>', 0)->get();
  @endphp

  <div id="stock-data"
       data-sin-stock="{{ $sinStock->count() }}"
       data-stock-bajo="{{ $stockBajo->count() }}"
       data-sin-stock-nombres="{{ $sinStock->pluck('nombre')->implode(', ') }}"
       data-stock-bajo-detalle="{{ $stockBajo->map(fn($p) => $p->nombre.' ('.$p->stock.' uds.)')->implode(', ') }}"
       data-productos-url="{{ route('productos.index') }}"
       style="display:none;">
  </div>

  <script>
    window.addEventListener('load', () => {
      const el        = document.getElementById('stock-data');
      if (!el) return;
      const sinStock  = parseInt(el.dataset.sinStock);
      const stockBajo = parseInt(el.dataset.stockBajo);
      const nombres   = el.dataset.sinStockNombres;
      const detalle   = el.dataset.stockBajoDetalle;
      const url       = el.dataset.productosUrl;

      if (sinStock > 0) {
        Swal.fire({
          icon: 'error',
          title: '⚠ Productos agotados',
          html: '<p style="color:rgba(255,255,255,0.7);margin-bottom:8px;">Productos sin stock:</p><p style="color:#fca5a5;">' + nombres + '</p>',
          background: '#1a1200',
          color: '#fff',
          confirmButtonColor: '#f97316',
          confirmButtonText: 'Ver productos',
        }).then(() => { window.location.href = url; });

      } else if (stockBajo > 0) {
        Swal.fire({
          icon: 'warning',
          title: 'Stock bajo',
          html: '<p style="color:rgba(255,255,255,0.7);margin-bottom:8px;">Poco stock en:</p><p style="color:#fdba74;">' + detalle + '</p>',
          background: '#1a1200',
          color: '#fff',
          confirmButtonColor: '#f97316',
          confirmButtonText: 'Revisar',
          showCancelButton: true,
          cancelButtonText: 'Ignorar',
          cancelButtonColor: '#6b7280',
        }).then((result) => {
          if (result.isConfirmed) window.location.href = url;
        });
      }
    });
  </script>
  @endif
  @endauth

</body>
</html>