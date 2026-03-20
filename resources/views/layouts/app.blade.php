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
        {{-- ← Agregar esta línea --}}
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

      <!-- @if(Auth::user()->role === 'cliente')
        <li class="nav-item">
          <a href="{{ route('cliente.dashboard') }}" class="{{ request()->routeIs('cliente.dashboard') ? 'active' : '' }}">
            Inicio
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('cliente.productos') }}" class="{{ request()->routeIs('cliente.productos') ? 'active' : '' }}">
            Productos
          </a>
        </li>
      @endif -->
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
</body>
</html>