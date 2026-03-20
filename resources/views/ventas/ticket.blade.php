@extends('layouts.app')
@section('content')

<div class="ticket-page">

  {{-- Header navegación --}}
  <div class="page-header">
    <a href="{{ route('ventas.index') }}" class="back-btn">←</a>
    <div>
      <p class="page-eyebrow">Ventas</p>
      <h1 class="page-title">Ticket #{{ $venta->id }}</h1>
    </div>
  </div>

  {{-- Ticket --}}
  <div class="ticket-card">

    {{-- Header naranja --}}
    <div class="ticket-header">
      <div class="ticket-brand">
        <img src="{{ asset('images/logo.png') }}" alt="Mercadex" class="ticket-brand-logo">
        <span class="ticket-brand-name">Mercadex</span>
      </div>
      <div class="ticket-id-row">
        <div>
          <div class="ticket-id-label">Folio de venta</div>
          <div class="ticket-id-val">#{{ str_pad($venta->id, 5, '0', STR_PAD_LEFT) }}</div>
        </div>
        <div class="ticket-status">✅ Completada</div>
      </div>
    </div>

    {{-- Línea de perforación --}}
    <div class="ticket-perf">
      <div class="perf-circle"></div>
      <div class="perf-line"></div>
      <div class="perf-circle"></div>
    </div>

    {{-- Cuerpo --}}
    <div class="ticket-body">

      {{-- Meta --}}
      <div class="ticket-meta">
        <div class="meta-item">
          <div class="meta-label">Cliente</div>
          <div class="meta-val">{{ $venta->user->name ?? 'Desconocido' }}</div>
        </div>
        <div class="meta-item">
          <div class="meta-label">Vendedor</div>
          <div class="meta-val">{{ Auth::user()->name }}</div>
        </div>
        <div class="meta-item">
          <div class="meta-label">Fecha</div>
          <div class="meta-val">{{ $venta->created_at->format('d/m/Y') }}</div>
        </div>
        <div class="meta-item">
          <div class="meta-label">Hora</div>
          <div class="meta-val">{{ $venta->created_at->format('H:i') }} hrs</div>
        </div>
      </div>

      {{-- Productos --}}
      <div class="items-header">
        <span>Producto</span>
        <span style="text-align:center">Cant.</span>
        <span class="right">Subtotal</span>
      </div>

      @foreach($venta->detalles as $d)
      <div class="item-row">
        <div class="item-nombre">{{ $d->producto->nombre }}</div>
        <div class="item-qty">× {{ $d->cantidad }}</div>
        <div class="item-subtotal">${{ number_format($d->subtotal, 2) }}</div>
      </div>
      @endforeach

      {{-- Total --}}
      <div class="ticket-total-section">
        <div class="total-row">
          <span class="total-row-label">Subtotal</span>
          <span class="total-row-val">${{ number_format($venta->total, 2) }}</span>
        </div>
        <div class="total-row">
          <span class="total-row-label">Descuentos</span>
          <span class="total-row-val">$0.00</span>
        </div>
        <div class="total-final-row">
          <span class="total-final-label">Total</span>
          <span class="total-final-val">${{ number_format($venta->total, 2) }}</span>
        </div>
      </div>

    </div>

    {{-- Footer ticket --}}
    <div class="ticket-footer">
      <p class="ticket-footer-text">
        ¡Gracias por tu compra en <strong>Mercadex</strong>! 🧡
      </p>
      <p class="ticket-footer-text" style="margin-top:0.3rem;">
        Folio {{ str_pad($venta->id, 5, '0', STR_PAD_LEFT) }} · {{ $venta->created_at->format('d/m/Y H:i') }}
      </p>
    </div>

  </div>

  {{-- Acciones --}}
  <div class="actions-row">
    <button class="btn-print" onclick="window.print()">🖨 Imprimir Ticket</button>
    <a href="{{ route('ventas.index') }}" class="btn-back">← Volver</a>
  </div>

</div>

@endsection