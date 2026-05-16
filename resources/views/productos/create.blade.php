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

{{-- Datos de subcategorías para JavaScript --}}
<div id="subcats-data"
     data-subcats="{{ json_encode($categorias->mapWithKeys(fn($cat) => [
       $cat->id => $cat->subcategorias->map(fn($sub) => [
         'id'     => $sub->id,
         'nombre' => $sub->nombre
       ])->values()
     ])) }}"
     data-subcat-actual="{{ old('subcategoria_id') }}"
     style="display:none;">
</div>

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
              placeholder="Ej. Coca Cola 600ml"
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

        {{-- Categoría y Subcategoría --}}
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">

          <div class="field-group">
            <label class="field-label" for="categoria_id">Categoría</label>
            <div class="field-wrap">
              <select id="categoria_id" name="categoria_id" class="field-input"
                style="padding-left:2.5rem; cursor:pointer; appearance:none; -webkit-appearance:none;"
                onchange="cargarSubcategorias(this)">
                <option value="">Sin categoría</option>
                @foreach($categorias as $cat)
                  <option value="{{ $cat->id }}"
                    {{ old('categoria_id') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->nombre }}
                  </option>
                @endforeach
              </select>
              <span class="field-icon">📁</span>
            </div>
            @error('categoria_id') <div class="field-error">⚠ {{ $message }}</div> @enderror
          </div>

          <div class="field-group">
            <label class="field-label" for="subcategoria_id">Subcategoría</label>
            <div class="field-wrap">
              <select id="subcategoria_id" name="subcategoria_id" class="field-input"
                style="padding-left:2.5rem; cursor:pointer; appearance:none; -webkit-appearance:none;">
                <option value="">Sin subcategoría</option>
              </select>
              <span class="field-icon">🔖</span>
            </div>
            @error('subcategoria_id') <div class="field-error">⚠ {{ $message }}</div> @enderror
          </div>

        </div>

        {{-- Proveedor --}}
        <div class="field-group">
          <label class="field-label" for="proveedor_id">Proveedor</label>
          <div class="field-wrap">
            <select id="proveedor_id" name="proveedor_id" class="field-input"
              style="padding-left:2.5rem; cursor:pointer; appearance:none; -webkit-appearance:none;">
              <option value="">Sin proveedor</option>
              @foreach($proveedores as $prov)
                <option value="{{ $prov->id }}"
                  {{ old('proveedor_id') == $prov->id ? 'selected' : '' }}>
                  {{ $prov->nombre }}
                </option>
              @endforeach
            </select>
            <span class="field-icon">🏭</span>
          </div>
          @error('proveedor_id') <div class="field-error">⚠ {{ $message }}</div> @enderror
        </div>

        {{-- Presentación --}}
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">

          <div class="field-group">
            <label class="field-label" for="presentacion">Tipo de presentación</label>
            <div class="field-wrap">
              <select id="presentacion" name="presentacion" class="field-input"
                style="padding-left:2.5rem; cursor:pointer; appearance:none; -webkit-appearance:none;"
                onchange="updatePresentacion()">
                <option value="unidad"  {{ old('presentacion', 'unidad') == 'unidad'  ? 'selected' : '' }}>📦 Unidad</option>
                <option value="caja"    {{ old('presentacion') == 'caja'    ? 'selected' : '' }}>📫 Caja</option>
                <option value="paquete" {{ old('presentacion') == 'paquete' ? 'selected' : '' }}>🎁 Paquete</option>
              </select>
              <span class="field-icon">🏷</span>
            </div>
            @error('presentacion') <div class="field-error">⚠ {{ $message }}</div> @enderror
          </div>

          <div class="field-group">
            <label class="field-label" for="unidades_por_presentacion">Unidades por presentación</label>
            <div class="field-wrap">
              <input type="number" id="unidades_por_presentacion" name="unidades_por_presentacion"
                class="field-input" placeholder="Ej. 24" min="1"
                value="{{ old('unidades_por_presentacion', 1) }}"
                oninput="updatePresentacion()">
              <span class="field-icon">🔢</span>
            </div>
            <p class="field-hint" id="presentacion-hint">1 unidad por presentación</p>
            @error('unidades_por_presentacion') <div class="field-error">⚠ {{ $message }}</div> @enderror
          </div>

        </div>

        {{-- Stock, Costo y Precio --}}
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">

          <div class="field-group">
            <label class="field-label" for="stock" id="stock-label">Cantidad a ingresar</label>
            <div class="field-wrap">
              <input type="number" id="stock" name="stock" class="field-input"
                placeholder="0" min="0"
                value="{{ old('stock') }}" required
                oninput="updatePresentacion(); updatePreview();">
              <span class="field-icon">🗂</span>
            </div>
            <p class="field-hint" id="stock-hint">Unidades disponibles</p>
            @error('stock') <div class="field-error">⚠ {{ $message }}</div> @enderror
          </div>

          <div class="field-group">
            <label class="field-label" for="costo">Costo de compra</label>
            <div class="field-wrap">
              <input type="number" id="costo" name="costo" class="field-input"
                placeholder="0.00" step="0.01" min="0"
                value="{{ old('costo', 0) }}"
                oninput="updatePreview()">
              <span class="input-prefix">$</span>
            </div>
            <p class="field-hint">Lo que pagas al proveedor</p>
            @error('costo') <div class="field-error">⚠ {{ $message }}</div> @enderror
          </div>

          <div class="field-group">
            <label class="field-label" for="precio">Precio de venta</label>
            <div class="field-wrap">
              <input type="number" id="precio" name="precio" class="field-input"
                placeholder="0.00" step="0.01" min="0"
                value="{{ old('precio') }}" required
                oninput="updatePreview()">
              <span class="input-prefix">$</span>
            </div>
            <p class="field-hint">Precio al público por unidad</p>
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
            <span class="preview-row-label">Presentación</span>
            <span class="preview-row-val" id="prev-presentacion">Unidad</span>
          </div>
          <div class="preview-row">
            <span class="preview-row-label">Stock real</span>
            <span class="preview-row-val" id="prev-stock-real" style="color:#f97316;">0 uds.</span>
          </div>
          <div class="preview-row">
            <span class="preview-row-label">Costo</span>
            <span class="preview-row-val" id="prev-costo">$0.00</span>
          </div>
          <div class="preview-row">
            <span class="preview-row-label">Precio venta</span>
            <span class="preview-row-val price" id="prev-price">$0.00</span>
          </div>
          <div class="preview-row">
            <span class="preview-row-label">Ganancia unit.</span>
            <span class="preview-row-val" id="prev-ganancia" style="color:#22c55e;">$0.00</span>
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
  // ── Subcategorías dinámicas ──────────────────────────
  const subcatsEl     = document.getElementById('subcats-data');
  const subcategorias = JSON.parse(subcatsEl.dataset.subcats);
  const subcatActual  = subcatsEl.dataset.subcatActual;

  function cargarSubcategorias(select) {
    const catId  = select.value;
    const subSel = document.getElementById('subcategoria_id');
    const subs   = subcategorias[catId] || [];

    subSel.innerHTML = '<option value="">Sin subcategoría</option>';

    subs.forEach(function(sub) {
      const opt       = document.createElement('option');
      opt.value       = sub.id;
      opt.textContent = sub.nombre;
      if (String(sub.id) === String(subcatActual)) opt.selected = true;
      subSel.appendChild(opt);
    });
  }

  const catSelect = document.getElementById('categoria_id');
  if (catSelect.value) cargarSubcategorias(catSelect);

  // ── Presentación ─────────────────────────────────────
  const presentacionNombres = { unidad: 'Unidad', caja: 'Caja', paquete: 'Paquete' };
  const presentacionIconos  = { unidad: '📦', caja: '📫', paquete: '🎁' };

  function updatePresentacion() {
    const tipo  = document.getElementById('presentacion').value;
    const uds   = parseInt(document.getElementById('unidades_por_presentacion').value) || 1;
    const cant  = parseInt(document.getElementById('stock').value) || 0;
    const stockReal = cant * uds;

    // Hint de presentación
    document.getElementById('presentacion-hint').textContent =
      uds === 1 ? '1 unidad por presentación'
                : uds + ' unidades por ' + tipo;

    // Label del stock
    document.getElementById('stock-label').textContent =
      tipo === 'unidad' ? 'Cantidad a ingresar'
                        : 'Cantidad de ' + tipo + 's a ingresar';

    // Hint del stock
    document.getElementById('stock-hint').textContent =
      tipo === 'unidad'
        ? 'Unidades disponibles'
        : 'Stock real: ' + stockReal + ' unidades (' + cant + ' ' + tipo + (cant !== 1 ? 's' : '') + ')';

    // Vista previa presentación
    document.getElementById('prev-presentacion').textContent =
      presentacionIconos[tipo] + ' ' + presentacionNombres[tipo] +
      (uds > 1 ? ' de ' + uds + ' uds.' : '');

    // Vista previa stock real
    document.getElementById('prev-stock-real').textContent = stockReal + ' uds. reales';
    document.getElementById('prev-stock').textContent      = stockReal + ' uds.';
  }

  // ── Vista previa ─────────────────────────────────────
  function updatePreview() {
    const name     = document.getElementById('nombre').value.trim();
    const desc     = document.getElementById('descripcion').value.trim();
    const precio   = parseFloat(document.getElementById('precio').value) || 0;
    const costo    = parseFloat(document.getElementById('costo').value)  || 0;
    const ganancia = precio - costo;

    document.getElementById('prev-name').textContent  = name || '—';
    document.getElementById('prev-desc').textContent  = desc || 'Sin descripción';
    document.getElementById('prev-price').textContent = '$' + precio.toFixed(2);
    document.getElementById('prev-costo').textContent = '$' + costo.toFixed(2);

    const ganEl = document.getElementById('prev-ganancia');
    ganEl.textContent = '$' + ganancia.toFixed(2);
    ganEl.style.color = ganancia >= 0 ? '#22c55e' : '#ef4444';

    updatePresentacion();
  }

  // Inicializar al cargar
  updatePresentacion();
</script>

@endsection