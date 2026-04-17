@extends('layouts.app')
@section('content')

<style>
  
</style>

{{-- Header --}}
<div class="page-header">
  <div>
    <p class="page-eyebrow">Inventario</p>
    <h1 class="page-title">Gestión de Productos</h1>
    <p class="page-count">{{ $productos->count() }} producto{{ $productos->count() !== 1 ? 's' : '' }} registrado{{ $productos->count() !== 1 ? 's' : '' }}</p>
  </div>
  <a href="{{ route('productos.create') }}" class="btn-new">
    ＋ Nuevo Producto
  </a>
</div>

{{-- Alert --}}
@if(session('success'))
  <div class="alert-ok">✅ {{ session('success') }}</div>
@endif

{{-- Tabla --}}
<div class="table-card">

  <div class="table-toolbar">
    <div class="search-wrap">
      <span class="search-icon">🔍</span>
      <input type="text" class="search-input" id="searchInput" placeholder="Buscar producto..." oninput="filterTable()">
    </div>
    <span class="toolbar-count" id="visibleCount">{{ $productos->count() }} resultados</span>
  </div>

  <div style="overflow-x: auto;">
    <table class="prod-table" id="prodTable">
      <thead>
        <tr>
          <th>ID</th>
          <th>Producto</th>
          <th>Descripción</th>
          <th class="center">Precio</th>
          <th class="center">Stock</th>
          <th class="center">Estado</th>
          <th class="center">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($productos as $producto)
        <tr>
          <td><span class="id-chip">{{ $producto->id }}</span></td>
          <td>
            <div class="prod-name">{{ $producto->nombre }}</div>
          </td>
          <td>
            <div class="prod-desc" title="{{ $producto->descripcion }}">
              {{ $producto->descripcion ?: '—' }}
            </div>
          </td>
          <td class="center">
            <span class="price-val">${{ number_format($producto->precio, 2) }}</span>
          </td>
          <td class="center">
            @if($producto->stock > 0)
              <span class="stock-badge ok">● {{ $producto->stock }}</span>
            @else
              <span class="stock-badge empty">✕ Sin stock</span>
            @endif
          </td>
          <td class="center">
            @if($producto->activo)
              <span class="estado-badge activo"><span class="estado-dot"></span> Activo</span>
            @else
              <span class="estado-badge inactivo"><span class="estado-dot"></span> Inactivo</span>
            @endif
          </td>
          <td class="center">
              <div class="actions-wrap" style="flex-direction:column; gap:0.5rem; align-items:center;">

                {{-- Stock rápido --}}
                <form action="{{ route('productos.updateStock', $producto) }}" method="POST"
                      style="display:flex; gap:0.4rem; align-items:center;">
                  @csrf @method('PATCH')
                  <input type="number" name="stock" value="{{ $producto->stock }}"
                        min="0" style="
                          width:60px; text-align:center;
                          background:rgba(255,255,255,0.04);
                          border:1px solid rgba(255,255,255,0.1);
                          border-radius:8px; color:#fff;
                          font-family:inherit; font-size:0.8rem;
                          padding:0.28rem 0.4rem; outline:none;">
                  <button type="submit" class="action-btn" style="
                          background:rgba(34,197,94,0.1);
                          border-color:rgba(34,197,94,0.2);
                          color:#86efac;">
                    ✓
                  </button>
                </form>

                {{-- Editar completo + Eliminar --}}
                <div style="display:flex; gap:0.4rem;">
                  <a href="{{ route('productos.edit', $producto) }}" class="action-btn" style="
                      background:rgba(249,115,22,0.1);
                      border-color:rgba(249,115,22,0.2);
                      color:#fdba74;">
                    ✏ Editar
                  </a>

                  @if($producto->activo)
                    <form action="{{ route('productos.destroy', $producto) }}" method="POST" class="form-delete">
                      @csrf @method('DELETE')
                      <button type="submit" class="action-btn danger">🗑</button>
                    </form>
                  @else
                    <span class="no-action">Inactivo</span>
                  @endif
                </div>

              </div>
            </td>
        </tr>
        @empty
        <tr>
          <td colspan="7">
            <div class="empty-state">
              <div class="empty-icon">📦</div>
              <div class="empty-title">Sin productos registrados</div>
              <div class="empty-sub">Agrega tu primer producto con el botón de arriba</div>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

</div>

<script>
  function filterTable() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('#prodTable tbody tr');
    let visible = 0;

    rows.forEach(row => {
      const text = row.innerText.toLowerCase();
      const show = text.includes(q);
      row.style.display = show ? '' : 'none';
      if (show) visible++;
    });

    document.getElementById('visibleCount').textContent = visible + ' resultado' + (visible !== 1 ? 's' : '');
  }
</script>

@endsection