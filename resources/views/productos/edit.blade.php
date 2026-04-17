@extends('layouts.app')
@section('content')

<style>
  .stock-section {
    background: rgba(249,115,22,0.05);
    border: 1px solid rgba(249,115,22,0.15);
    border-radius: 14px;
    padding: 1rem 1.2rem;
  }
</style>

{{-- Header --}}
<div class="page-header">
  <a href="{{ route('productos.index') }}" class="back-btn">←</a>
  <div>
    <p class="page-eyebrow">Productos</p>
    <h1 class="page-title">Editar Producto</h1>
    <p class="page-subtitle">ID #{{ $producto->id }} · Última edición {{ $producto->updated_at->diffForHumans() }}</p>
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

<form method="POST" action="{{ route('productos.update', $producto) }}" id="prodForm">
  @csrf @method('PUT')
  <div class="form-layout">

    {{-- Columna principal --}}
    <div class="form-card">
      <div class="id-info">
        <span class="id-chip-sm">#{{ $producto->id }}</span>
        <span>Editando: <strong style="color:rgba(255,255,255,0.5)">{{ $producto->nombre }}</strong></span>
      </div>
      <div class="card-section-title">Información del producto</div>
      <div class="card-body-pad">

        {{-- Nombre --}}
        <div class="field-group">
          <label class="field-label" for="nombre">Nombre del producto</label>
          <div class="field-wrap">
            <input type="text" id="nombre" name="nombre" class="field-input"
              placeholder="Nombre del producto"
              value="{{ old('nombre', $producto->nombre) }}"
              required oninput="updatePreview()">
            <span class="field-icon">📦</span>
          </div>
          @error('nombre') <div class="field-error">⚠ {{ $message }}</div> @enderror
        </div>

        {{-- Descripción --}}
        <div class="field-group">
          <label class="field-label" for="descripcion">Descripción</label>
          <div class="field-wrap">
            <textarea id="descripcion" name="descripcion" class="field-textarea"
              placeholder="Describe el producto..."
              oninput="updatePreview()">{{ old('descripcion', $producto->descripcion) }}</textarea>
            <span class="field-icon top">📝</span>
          </div>
          @error('descripcion') <div class="field-error">⚠ {{ $message }}</div> @enderror
        </div>

        {{-- Categoría y Proveedor --}}
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">

          <div class="field-group">
            <label class="field-label" for="categoria_id">Categoría</label>
            <div class="field-wrap">
              <select id="categoria_id" name="categoria_id" class="field-input"
                style="padding-left:2.5rem; cursor:pointer; appearance:none; -webkit-appearance:none;">
                <option value="">Sin categoría</option>
                @foreach($categorias as $cat)
                  <option value="{{ $cat->id }}"
                    {{ old('categoria_id', $producto->categoria_id) == $cat->id ? 'selected' : '' }}>
                    {{ $cat->nombre }}
                  </option>
                @endforeach
              </select>
              <span class="field-icon">🏷</span>
            </div>
            @error('categoria_id') <div class="field-error">⚠ {{ $message }}</div> @enderror
          </div>

          <div class="field-group">
            <label class="field-label" for="proveedor_id">Proveedor</label>
            <div class="field-wrap">
              <select id="proveedor_id" name="proveedor_id" class="field-input"
                style="padding-left:2.5rem; cursor:pointer; appearance:none; -webkit-appearance:none;">
                <option value="">Sin proveedor</option>
                @foreach($proveedores as $prov)
                  <option value="{{ $prov->id }}"
                    {{ old('proveedor_id', $producto->proveedor_id) == $prov->id ? 'selected' : '' }}>
                    {{ $prov->nombre }}
                  </option>
                @endforeach
              </select>
              <span class="field-icon">🏭</span>
            </div>
            @error('proveedor_id') <div class="field-error">⚠ {{ $message }}</div> @enderror
          </div>

        </div>

        {{-- Stock, Costo y Precio --}}
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">

          {{-- Stock destacado --}}
          <div>
            <div class="stock-section">
              <label class="field-label" for="stock" style="color:#f97316;">📦 Stock actual</label>
              <div class="field-wrap">
                <input type="number" id="stock" name="stock" class="field-input"
                  placeholder="0" min="0"
                  value="{{ old('stock', $producto->stock) }}"
                  required oninput="updatePreview()">
                <span class="field-icon">🗂</span>
              </div>
              <p class="field-hint" style="color:rgba(249,115,22,0.5);">Unidades en inventario</p>
            </div>
            @error('stock') <div class="field-error">⚠ {{ $message }}</div> @enderror
          </div>

          {{-- Costo --}}
          <div class="field-group">
            <label class="field-label" for="costo">Costo de compra</label>
            <div class="field-wrap">
              <input type="number" id="costo" name="costo" class="field-input"
                placeholder="0.00" step="0.01" min="0"
                value="{{ old('costo', $producto->costo) }}"
                oninput="updatePreview()">
              <span class="input-prefix">$</span>
            </div>
            <p class="field-hint">Lo que pagas al proveedor</p>
            @error('costo') <div class="field-error">⚠ {{ $message }}</div> @enderror
          </div>

          {{-- Precio --}}
          <div class="field-group">
            <label class="field-label" for="precio">Precio de venta</label>
            <div class="field-wrap">
              <input type="number" id="precio" name="precio" class="field-input"
                placeholder="0.00" step="0.01" min="0"
                value="{{ old('precio', $producto->precio) }}"
                required oninput="updatePreview()">
              <span class="input-prefix">$</span>
            </div>
            <p class="field-hint">Precio al público</p>
            @error('precio') <div class="field-error">⚠ {{ $message }}</div> @enderror
          </div>

        </div>

      </div>
    </div>

    {{-- Panel lateral --}}
    <div class="side-panel">

      {{-- Vista previa --}}
      <div class="preview-card">
        <div class="preview-inner">
          <div class="preview-label">Vista previa</div>
          <div class="preview-name" id="prev-name">{{ $producto->nombre }}</div>
          <div class="preview-desc" id="prev-desc">{{ $producto->descripcion ?: 'Sin descripción' }}</div>
          <div class="preview-row">
            <span class="preview-row-label">Costo</span>
            <span class="preview-row-val" id="prev-costo">${{ number_format($producto->costo, 2) }}</span>
          </div>
          <div class="preview-row">
            <span class="preview-row-label">Precio venta</span>
            <span class="preview-row-val price" id="prev-price">${{ number_format($producto->precio, 2) }}</span>
          </div>
          <div class="preview-row">
            <span class="preview-row-label">Ganancia unit.</span>
            <span class="preview-row-val" id="prev-ganancia" style="color:#22c55e;">
              ${{ number_format($producto->precio - $producto->costo, 2) }}
            </span>
          </div>
          <div class="preview-row">
            <span class="preview-row-label">Stock</span>
            <span class="preview-row-val" id="prev-stock">{{ $producto->stock }} uds.</span>
          </div>
          <div class="preview-row">
            <span class="preview-row-label">Estado</span>
            <span class="preview-badge">● Activo</span>
          </div>
        </div>
      </div>

      {{-- Botones --}}
      <div class="form-card">
        <div class="card-section-title">Guardar cambios</div>
        <div class="card-body-pad" style="display:flex; flex-direction:column; gap:0.75rem;">
          <button type="submit" class="btn-submit">💾 Guardar cambios</button>
          <a href="{{ route('productos.index') }}" class="btn-cancel">Cancelar</a>
        </div>
      </div>

    </div>
  </div>
</form>

<script>
  function updatePreview() {
    const name     = document.getElementById('nombre').value.trim();
    const desc     = document.getElementById('descripcion').value.trim();
    const stock    = document.getElementById('stock').value;
    const precio   = parseFloat(document.getElementById('precio').value) || 0;
    const costo    = parseFloat(document.getElementById('costo').value)  || 0;
    const ganancia = precio - costo;

    document.getElementById('prev-name').textContent  = name  || '—';
    document.getElementById('prev-desc').textContent  = desc  || 'Sin descripción';
    document.getElementById('prev-stock').textContent = (stock !== '' ? stock : '0') + ' uds.';
    document.getElementById('prev-price').textContent = '$' + precio.toFixed(2);
    document.getElementById('prev-costo').textContent = '$' + costo.toFixed(2);

    const ganEl = document.getElementById('prev-ganancia');
    ganEl.textContent = '$' + ganancia.toFixed(2);
    ganEl.style.color = ganancia >= 0 ? '#22c55e' : '#ef4444';
  }
</script>

@endsection