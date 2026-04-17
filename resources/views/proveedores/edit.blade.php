@extends('layouts.app')
@section('content')

<style>
  .page-header { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.75rem; animation: fadeUp 0.4s cubic-bezier(0.16,1,0.3,1) both; }
  .back-btn { display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 10px; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); color: rgba(255,255,255,0.45); text-decoration: none; font-size: 1rem; transition: all 0.18s; flex-shrink: 0; }
  .back-btn:hover { background: rgba(249,115,22,0.1); border-color: rgba(249,115,22,0.3); color: #f97316; }
  .page-eyebrow { font-size: 0.72rem; font-weight: 700; color: #f97316; letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 0.2rem; }
  .page-title { font-size: 1.6rem; font-weight: 800; color: #fff; margin: 0; line-height: 1.15; }
  .form-layout { display: grid; grid-template-columns: 1fr 300px; gap: 1.25rem; animation: fadeUp 0.4s 0.07s cubic-bezier(0.16,1,0.3,1) both; align-items: start; max-width: 860px; }
  .form-card { background: rgba(255,255,255,0.025); border: 1px solid rgba(255,255,255,0.07); border-radius: 18px; overflow: hidden; }
  .card-section-title { font-size: 0.68rem; font-weight: 700; color: rgba(255,255,255,0.25); text-transform: uppercase; letter-spacing: 0.1em; padding: 1.1rem 1.4rem 0.6rem; border-bottom: 1px solid rgba(255,255,255,0.05); }
  .card-body-pad { padding: 1.4rem; }
  .field-group { margin-bottom: 1.25rem; }
  .field-group:last-child { margin-bottom: 0; }
  .field-label { display: block; font-size: 0.72rem; font-weight: 700; color: rgba(255,255,255,0.42); letter-spacing: 0.07em; text-transform: uppercase; margin-bottom: 0.45rem; }
  .field-wrap { position: relative; }
  .field-icon { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); font-size: 0.9rem; color: rgba(255,255,255,0.17); pointer-events: none; transition: color 0.2s; }
  .field-input { width: 100%; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; color: #fff; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.88rem; padding: 0.75rem 0.9rem 0.75rem 2.5rem; outline: none; transition: border-color 0.2s, background 0.2s, box-shadow 0.2s; }
  .field-input::placeholder { color: rgba(255,255,255,0.15); }
  .field-input:focus { background: rgba(249,115,22,0.06); border-color: rgba(249,115,22,0.5); box-shadow: 0 0 0 3px rgba(249,115,22,0.1); color: #fff; }
  .field-wrap:focus-within .field-icon { color: rgba(249,115,22,0.6); }
  .field-error { font-size: 0.73rem; color: #fca5a5; margin-top: 0.3rem; }
  .alert-error { background: rgba(239,68,68,0.09); border: 1px solid rgba(239,68,68,0.22); border-radius: 12px; padding: 0.8rem 1.1rem; margin-bottom: 1.4rem; font-size: 0.82rem; color: #fca5a5; }
  .side-panel { display: flex; flex-direction: column; gap: 1.1rem; }
  .preview-card { background: rgba(255,255,255,0.025); border: 1px solid rgba(255,255,255,0.07); border-radius: 18px; overflow: hidden; }
  .preview-inner { padding: 1.4rem; }
  .preview-avatar { width: 52px; height: 52px; border-radius: 50%; background: linear-gradient(135deg,rgba(249,115,22,0.3),rgba(234,179,8,0.3)); border: 2px solid rgba(249,115,22,0.25); display: flex; align-items: center; justify-content: center; font-size: 1.3rem; font-weight: 800; color: #f97316; margin: 0 auto 0.75rem; text-transform: uppercase; transition: all 0.2s; }
  .preview-nombre { font-size: 0.95rem; font-weight: 700; color: #fff; text-align: center; margin-bottom: 0.25rem; }
  .preview-sub { font-size: 0.75rem; color: rgba(255,255,255,0.3); text-align: center; }
  .btn-submit { width: 100%; padding: 0.82rem; background: linear-gradient(135deg,#f97316,#eab308); border: none; border-radius: 12px; color: #0e0a00; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.9rem; font-weight: 800; cursor: pointer; transition: transform 0.15s, box-shadow 0.2s, opacity 0.2s; box-shadow: 0 5px 18px rgba(249,115,22,0.35); }
  .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 26px rgba(249,115,22,0.45); opacity: 0.93; }
  .btn-cancel { display: block; width: 100%; padding: 0.7rem; background: transparent; border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; color: rgba(255,255,255,0.35); font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.85rem; font-weight: 600; text-decoration: none; text-align: center; transition: all 0.18s; }
  .btn-cancel:hover { border-color: rgba(255,255,255,0.18); color: rgba(255,255,255,0.6); }
  @keyframes fadeUp { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: translateY(0); } }
  @media (max-width: 760px) { .form-layout { grid-template-columns: 1fr; } }
</style>

<div class="page-header">
  <a href="{{ route('proveedores.index') }}" class="back-btn">←</a>
  <div>
    <p class="page-eyebrow">Proveedores</p>
    <h1 class="page-title">{{ isset($proveedor) ? 'Editar Proveedor' : 'Nuevo Proveedor' }}</h1>
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

<form method="POST" action="{{ isset($proveedor) ? route('proveedores.update', $proveedor) : route('proveedores.store') }}">
  @csrf
  @if(isset($proveedor)) @method('PUT') @endif
  <div class="form-layout">

    <div class="form-card">
      <div class="card-section-title">Datos del proveedor</div>
      <div class="card-body-pad">

        <div class="field-group">
          <label class="field-label" for="nombre">Nombre del proveedor</label>
          <div class="field-wrap">
            <input type="text" id="nombre" name="nombre" class="field-input"
              placeholder="Ej. Distribuidora López"
              value="{{ old('nombre', $proveedor->nombre ?? '') }}"
              required oninput="updatePreview()">
            <span class="field-icon">🏭</span>
          </div>
          @error('nombre') <div class="field-error">⚠ {{ $message }}</div> @enderror
        </div>

        <div class="field-group">
          <label class="field-label" for="telefono">Teléfono de contacto</label>
          <div class="field-wrap">
            <input type="text" id="telefono" name="telefono" class="field-input"
              placeholder="Ej. 555-123-4567"
              value="{{ old('telefono', $proveedor->telefono ?? '') }}"
              oninput="updatePreview()">
            <span class="field-icon">📞</span>
          </div>
          @error('telefono') <div class="field-error">⚠ {{ $message }}</div> @enderror
        </div>

        <div class="field-group">
          <label class="field-label" for="email">Correo electrónico</label>
          <div class="field-wrap">
            <input type="email" id="email" name="email" class="field-input"
              placeholder="contacto@proveedor.com"
              value="{{ old('email', $proveedor->email ?? '') }}"
              oninput="updatePreview()">
            <span class="field-icon">✉</span>
          </div>
          @error('email') <div class="field-error">⚠ {{ $message }}</div> @enderror
        </div>

      </div>
    </div>

    <div class="side-panel">

      <div class="preview-card">
        <div class="preview-inner">
          <div class="preview-avatar" id="prevAvatar">{{ isset($proveedor) ? strtoupper(substr($proveedor->nombre,0,1)) : '?' }}</div>
          <div class="preview-nombre" id="prevNombre">{{ $proveedor->nombre ?? 'Nombre del proveedor' }}</div>
          <div class="preview-sub" id="prevEmail">{{ $proveedor->email ?? 'Sin correo' }}</div>
          <div class="preview-sub" id="prevTel" style="margin-top:0.2rem;">{{ $proveedor->telefono ?? 'Sin teléfono' }}</div>
        </div>
      </div>

      <div class="form-card">
        <div class="card-section-title">Guardar</div>
        <div class="card-body-pad" style="display:flex;flex-direction:column;gap:0.75rem;">
          <button type="submit" class="btn-submit">
            💾 {{ isset($proveedor) ? 'Guardar cambios' : 'Crear proveedor' }}
          </button>
          <a href="{{ route('proveedores.index') }}" class="btn-cancel">Cancelar</a>
        </div>
      </div>

    </div>
  </div>
</form>

<script>
  function updatePreview() {
    const nombre = document.getElementById('nombre').value.trim();
    const email  = document.getElementById('email').value.trim();
    const tel    = document.getElementById('telefono').value.trim();
    document.getElementById('prevAvatar').textContent  = nombre ? nombre.charAt(0).toUpperCase() : '?';
    document.getElementById('prevNombre').textContent  = nombre || 'Nombre del proveedor';
    document.getElementById('prevEmail').textContent   = email  || 'Sin correo';
    document.getElementById('prevTel').textContent     = tel    || 'Sin teléfono';
  }
</script>

@endsection