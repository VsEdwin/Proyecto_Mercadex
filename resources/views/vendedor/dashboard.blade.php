@extends('layouts.app')
@section('content')


{{-- Header --}}
<div class="dash-header">
  <p class="dash-greeting">Panel de vendedor</p>
  <h1 class="dash-title">Hola, <span>{{ Auth::user()->name }}</span></h1>
  <p class="dash-subtitle">Gestiona tus ventas y consulta tus registros desde aquí.</p>
</div>

{{-- Stats --}}
<div class="stats-grid">
  <div class="stat-card">
    <span class="stat-icon">🧾</span>
    <div class="stat-value">{{ $ventasHoy }}</div>
    <div class="stat-label">Ventas hoy</div>
  </div>
  <div class="stat-card">
    <span class="stat-icon">📦</span>
    <div class="stat-value">{{ $totalVentas }}</div>
    <div class="stat-label">Ventas totales</div>
  </div>
  <div class="stat-card">
    <span class="stat-icon">💰</span>
    <div class="stat-value">${{ number_format($ingresoHoy, 2) }}</div>
    <div class="stat-label">Ingreso hoy</div>
  </div>
  <div class="stat-card">
    <span class="stat-icon">📅</span>
    <div class="stat-value">{{ now()->format('d/m') }}</div>
    <div class="stat-label">Fecha actual</div>
  </div>
</div>

{{-- Acciones --}}
<div class="actions-grid">
  <a href="{{ route('ventas.create') }}" class="action-card primary">
    <div class="action-icon-wrap orange">➕</div>
    <div class="action-text">
      <div class="action-title">Registrar nueva venta</div>
      <div class="action-desc">Agrega productos y genera el ticket</div>
    </div>
    <span class="action-arrow">→</span>
  </a>

  <a href="{{ route('ventas.index') }}" class="action-card">
    <div class="action-icon-wrap outline">🧾</div>
    <div class="action-text">
      <div class="action-title">Ver ventas realizadas</div>
      <div class="action-desc">Consulta y descarga tus tickets anteriores</div>
    </div>
    <span class="action-arrow">→</span>
  </a>
</div>

{{-- Tip --}}
<div class="tip-card">
  💡 <span>Tip:</span> Puedes consultar el ticket de cualquier venta desde el historial de ventas.
</div>

@endsection