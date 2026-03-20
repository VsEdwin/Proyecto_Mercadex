@extends('layouts.app')
@section('content')

{{-- Header --}}
<div class="page-header">
  <a href="{{ route('productos.index') }}" class="back-btn" title="Volver">←</a>
  <div>
    <p class="page-eyebrow">Productos</p>
    <h1 class="page-title">Nuevo Producto</h1>
  </div>
</div>

@if($errors->any())
  <div class="alert-error">
    <strong>⚠ Corrige los siguientes errores:</strong>
    <ul class="mb-0 mt-1 ps-3">
      @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

<form method="POST" action="{{ route('productos.store') }}" id="prodForm">
  @csrf
  <div class="form-layout">

    {{-- Columna principal --}}
    <div class="form-card">
      <div class="card-section-title">Información del producto</div>
      <div class="card-body-pad">

        {{-- Nombre --}}
        <div class="field-group">
          <label class="field-label" for="nombre">Nombre del producto</label>
          <div class="field-wrap">
            <input type="text" id="nombre" name="nombre" class="field-input"
              placeholder="Ej. Camiseta manga corta"
              value="{{ old('nombre') }}" required
              oninput="updatePreview()">
            <span class="field-icon">📦</span>
          </div>
          @error('nombre') <div class="field-error">⚠ {{ $message }}</div> @enderror
        </div>

        {{-- Descripción --}}
        <div class="field-group">
          <label class="field-label" for="descripcion">Descripción</label>
          <div class="field-wrap">
            <textarea id="descripcion" name="descripcion" class="field-textarea"
              placeholder="Describe el producto brevemente..."
              oninput="updatePreview()">{{ old('descripcion') }}</textarea>
            <span class="field-icon top">📝</span>
          </div>
          @error('descripcion') <div class="field-error">⚠ {{ $message }}</div> @enderror
        </div>

        {{-- Stock y Precio en fila --}}
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">

          {{-- Stock --}}
          <div class="field-group">
            <label class="field-label" for="stock">Stock</label>
            <div class="field-wrap">
              <input type="number" id="stock" name="stock" class="field-input"
                placeholder="0" min="0"
                value="{{ old('stock') }}" required
                oninput="updatePreview()">
              <span class="field-icon">🗂</span>
            </div>
            <p class="field-hint">Unidades disponibles</p>
            @error('stock') <div class="field-error">⚠ {{ $message }}</div> @enderror
          </div>

          {{-- Precio --}}
          <div class="field-group">
            <label class="field-label" for="precio">Precio (MXN)</label>
            <div class="field-wrap">
              <input type="number" id="precio" name="precio" class="field-input"
                placeholder="0.00" step="0.01" min="0"
                value="{{ old('precio') }}" required
                oninput="updatePreview()">
              <span class="input-prefix">$</span>
            </div>
            <p class="field-hint">Precio de venta al público</p>
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
          <div class="preview-name" id="prev-name">—</div>
          <div class="preview-desc" id="prev-desc">Sin descripción</div>
          <div class="preview-row">
            <span class="preview-row-label">Precio</span>
            <span class="preview-row-val price" id="prev-price">$0.00</span>
          </div>
          <div class="preview-row">
            <span class="preview-row-label">Stock</span>
            <span class="preview-row-val" id="prev-stock">0 uds.</span>
          </div>
          <div class="preview-row">
            <span class="preview-row-label">Estado</span>
            <span class="preview-badge">● Activo</span>
          </div>
        </div>
      </div>

      {{-- Botones --}}
      <div class="form-card">
        <div class="card-section-title">Guardar</div>
        <div class="card-body-pad" style="display:flex; flex-direction:column; gap:0.75rem;">
          <button type="submit" class="btn-submit">💾 Guardar producto</button>
          <a href="{{ route('productos.index') }}" class="btn-cancel">Cancelar</a>
        </div>
      </div>

    </div>
  </div>
</form>

<script>
  function updatePreview() {
    const name  = document.getElementById('nombre').value.trim();
    const desc  = document.getElementById('descripcion').value.trim();
    const stock = document.getElementById('stock').value;
    const price = parseFloat(document.getElementById('precio').value);

    document.getElementById('prev-name').textContent  = name  || '—';
    document.getElementById('prev-desc').textContent  = desc  || 'Sin descripción';
    document.getElementById('prev-stock').textContent = (stock !== '' ? stock : '0') + ' uds.';
    document.getElementById('prev-price').textContent = isNaN(price) ? '$0.00' : '$' + price.toFixed(2);
  }
</script>

@endsection