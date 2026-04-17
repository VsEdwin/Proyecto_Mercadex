@extends('layouts.app')
@section('content')

{{-- Header --}}
<div class="dash-header">
    <p class="dash-greeting">Panel de administrador</p>
    <h1 class="dash-title">Hola, <span>{{ Auth::user()->name }}</span></h1>
    <p class="dash-subtitle">Tienes control total sobre productos, ventas y el sistema.</p>
</div>

{{-- Stats --}}
<div class="stats-grid">
  <div class="stat-card">
    <span class="stat-icon">📦</span>
    <div class="stat-value">{{ $totalProductos }}</div>
    <div class="stat-label">Productos</div>
  </div>
  <div class="stat-card">
    <span class="stat-icon">🧾</span>
    <div class="stat-value">{{ $totalVentas }}</div>
    <div class="stat-label">Ventas totales</div>
  </div>
  <div class="stat-card">
    <span class="stat-icon">👥</span>
    <div class="stat-value">{{ $totalUsuarios }}</div>
    <div class="stat-label">Usuarios</div>
  </div>
  <div class="stat-card">
    <span class="stat-icon">💰</span>
    <div class="stat-value">${{ number_format($totalIngresos, 2) }}</div>
    <div class="stat-label">Ingresos</div>
  </div>
  <div class="stat-card">
    <span class="stat-icon">📅</span>
    <div class="stat-value">{{ now()->format('d/m') }}</div>
    <div class="stat-label">Hoy</div>
  </div>
  <div class="stat-card">
    <span class="stat-icon">📉</span>
    <div class="stat-value">${{ number_format($totalInversion, 2) }}</div>
    <div class="stat-label">Inversión en stock</div>
    </div>
    <div class="stat-card">
        <span class="stat-icon">📈</span>
        <div class="stat-value">${{ number_format($totalGanancia, 2) }}</div>
        <div class="stat-label">Ganancia potencial</div>
    </div>
</div>

{{-- Acciones principales --}}
<p class="section-title">Acciones principales</p>
<div class="main-actions">
    <a href="{{ route('productos.index') }}" class="action-card featured">
        <div class="action-icon grad">📦</div>
        <div class="action-text">
            <div class="action-name">Administrar Productos</div>
            <div class="action-desc">Agrega, edita o elimina productos del catálogo</div>
        </div>
        <span class="action-arrow">→</span>
    </a>

    <a href="{{ route('ventas.index') }}" class="action-card">
        <div class="action-icon green">🧾</div>
        <div class="action-text">
            <div class="action-name">Ver Ventas</div>
            <div class="action-desc">Consulta el historial completo de ventas y tickets</div>
        </div>
        <span class="action-arrow">→</span>
    </a>

    <a href="{{ route('productos.create') }}" class="action-card">
        <div class="action-icon soft">➕</div>
        <div class="action-text">
            <div class="action-name">Nuevo Producto</div>
            <div class="action-desc">Añade un producto nuevo al inventario</div>
        </div>
        <span class="action-arrow">→</span>
    </a>
</div>

<!-- {{-- Quick links --}}
<p class="section-title">Acceso rápido</p>
<div class="quick-links">
    <a href="{{ route('productos.index') }}" class="quick-link">
        <span class="quick-link-icon">📋</span>
        <span class="quick-link-label">Inventario</span>
    </a>
    <a href="{{ route('ventas.index') }}" class="quick-link">
        <span class="quick-link-icon">📊</span>
        <span class="quick-link-label">Reportes</span>
    </a>
    <a href="{{ route('productos.create') }}" class="quick-link">
        <span class="quick-link-icon">📦</span>
        <span class="quick-link-label">Agregar</span>
    </a>
    <a href="{{ route('ventas.create') }}" class="quick-link">
        <span class="quick-link-icon">🧾</span>
        <span class="quick-link-label">Venta</span>
    </a>
</div> -->

<!-- {{-- Status --}}
<div class="info-strip">
    <div class="info-dot"></div>
    <span>Sistema operando con normalidad</span>
    <span>·</span>
    <span>Sesión iniciada como <strong>{{ Auth::user()->name }}</strong></span>
    <span>·</span>
    <span>{{ now()->format('l d \d\e F, Y') }}</span>
</div> -->

@endsection