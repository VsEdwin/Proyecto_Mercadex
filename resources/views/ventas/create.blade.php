@extends('layouts.app')
@section('content')

{{-- Header --}}
<div class="page-header">
  <a href="{{ route('ventas.index') }}" class="back-btn" title="Volver">←</a>
  <div>
    <p class="page-eyebrow">Ventas</p>
    <h1 class="page-title">Nueva Venta</h1>
  </div>
</div>

@if($errors->any())
  <div class="alert-error">
    <strong>⚠ Corrige los siguientes errores:</strong>
    <ul class="mb-0 mt-1 ps-3">
      @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
    </ul>
  </div>
@endif

<form method="POST" action="{{ route('ventas.store') }}" id="ventaForm">
  @csrf
  <div class="venta-layout">

    {{-- Lista de productos --}}
    <div class="form-card">
      <div class="card-section-title">
        Productos disponibles
        <span class="card-count-badge" id="selCount">0 seleccionados</span>
      </div>

      <div class="prod-search-wrap">
        <div class="prod-search">
          <span class="prod-search-icon">🔍</span>
          <input type="text" class="prod-search-input" placeholder="Buscar producto..." oninput="filterProds(this.value)">
        </div>
      </div>

      @php $disponibles = $productos->where('stock', '>', 0); @endphp

      @if($disponibles->isEmpty())
        <div class="empty-prods">
          <div class="empty-prods-icon">📦</div>
          <div class="empty-prods-text">No hay productos con stock disponible</div>
        </div>
      @else
        <div class="prod-list" id="prodList">
          @foreach($disponibles as $p)
          <div class="prod-row" data-search="{{ strtolower($p->nombre) }}">
            <div class="prod-info">
              <div class="prod-nombre">{{ $p->nombre }}</div>
              <div class="prod-meta">
                <span class="prod-precio">${{ number_format($p->precio, 2) }}</span>
                <span class="prod-stock {{ $p->stock <= 5 ? 'stock-low' : '' }}">
                  {{ $p->stock <= 5 ? '⚠' : '' }} {{ $p->stock }} en stock
                </span>
              </div>
            </div>

            <div class="qty-wrap">
              <button type="button" class="qty-btn" 
              onclick="changeQty(this.dataset.id, -1)" 
              data-id="{{ $p->id }}">−</button>
              <input type="number"
                     id="qty_{{ $p->id }}"
                     name="cantidad[]"
                     class="qty-input"
                     value="0" min="0" max="{{ $p->stock }}"
                     data-id="{{ $p->id }}"
                     data-precio="{{ $p->precio }}"
                     data-nombre="{{ $p->nombre }}"
                     data-max="{{ $p->stock }}"
                     oninput="onQtyChange(this)">
              <button type="button" class="qty-btn" 
              onclick="changeQty(this.dataset.id, 1)" 
              data-id="{{ $p->id }}">＋</button>
            </div>

            <div class="row-subtotal" id="sub_{{ $p->id }}">$0.00</div>

            <input type="hidden" name="producto_id[]" value="{{ $p->id }}">
            <input type="hidden" name="precio[]" value="{{ $p->precio }}">
          </div>
          @endforeach
        </div>
      @endif
    </div>

    {{-- Panel lateral --}}
    <div class="side-panel">

      {{-- Resumen --}}
      <div class="form-card">
        <div class="card-section-title">Resumen del pedido</div>
        <div class="resumen-body">
          <div id="resumenItems">
            <div class="resumen-empty" id="resumenEmpty">Aún no has seleccionado productos</div>
          </div>
          <div class="resumen-divider"></div>
          <div class="resumen-total-row">
            <span class="resumen-total-label">Total</span>
            <span class="resumen-total-val" id="totalVal">$0.00</span>
          </div>
        </div>
      </div>

      {{-- Botones --}}
      <div class="form-card">
        <div class="card-section-title">Confirmar</div>
        <div style="padding: 1.25rem 1.4rem; display: flex; flex-direction: column; gap: 0.75rem;">
          <button type="submit" class="btn-submit" id="submitBtn" disabled>
            🧾 Registrar Venta
          </button>
          <a href="{{ route('ventas.index') }}" class="btn-cancel">Cancelar</a>
          <p class="btn-hint">Selecciona al menos un producto para continuar</p>
        </div>
      </div>

    </div>
  </div>
</form>

<script>
  const precios = {};
  const nombres = {};
  const maximos = {};

  document.querySelectorAll('.qty-input').forEach(input => {
    const id = input.dataset.id;
    precios[id] = parseFloat(input.dataset.precio);
    nombres[id] = input.dataset.nombre;
    maximos[id] = parseInt(input.dataset.max);
  });

  function changeQty(id, delta) {
    const input = document.getElementById('qty_' + id);
    let val = parseInt(input.value) || 0;
    val = Math.max(0, Math.min(maximos[id], val + delta));
    input.value = val;
    onQtyChange(input);
  }

  function onQtyChange(input) {
    const id  = input.dataset.id;
    let val   = parseInt(input.value) || 0;
    val       = Math.max(0, Math.min(maximos[id], val));
    input.value = val;

    // Subtotal fila
    const sub = val * precios[id];
    const subEl = document.getElementById('sub_' + id);
    subEl.textContent = '$' + sub.toFixed(2);
    subEl.classList.toggle('active', val > 0);

    recalcular();
  }

  function recalcular() {
    let total = 0;
    let count = 0;
    const items = [];

    document.querySelectorAll('.qty-input').forEach(input => {
      const id  = input.dataset.id;
      const qty = parseInt(input.value) || 0;
      if (qty > 0) {
        total += qty * precios[id];
        count++;
        items.push({ id, qty, nombre: nombres[id], precio: precios[id] });
      }
    });

    // Total
    document.getElementById('totalVal').textContent = '$' + total.toFixed(2);

    // Contador
    document.getElementById('selCount').textContent = count + ' seleccionado' + (count !== 1 ? 's' : '');

    // Resumen
    const container = document.getElementById('resumenItems');
    const empty     = document.getElementById('resumenEmpty');

    if (items.length === 0) {
      container.innerHTML = '';
      container.appendChild(empty);
      empty.style.display = '';
    } else {
      empty.style.display = 'none';
      container.innerHTML = '';
      items.forEach(item => {
        const div = document.createElement('div');
        div.className = 'resumen-item';
        div.innerHTML = `
          <div>
            <div class="resumen-item-name">${item.nombre}</div>
            <div class="resumen-item-qty">${item.qty} × $${item.precio.toFixed(2)}</div>
          </div>
          <div class="resumen-item-price">$${(item.qty * item.precio).toFixed(2)}</div>
        `;
        container.appendChild(div);
      });
    }

    // Botón submit
    document.getElementById('submitBtn').disabled = count === 0;
  }

  function filterProds(q) {
    const rows = document.querySelectorAll('#prodList .prod-row');
    q = q.toLowerCase();
    rows.forEach(row => {
      row.style.display = row.dataset.search.includes(q) ? '' : 'none';
    });
  }
</script>

@endsection