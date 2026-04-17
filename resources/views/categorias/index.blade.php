@extends('layouts.app')
@section('content')

<style>
  .page-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; margin-bottom: 1.75rem; animation: fadeUp 0.4s cubic-bezier(0.16,1,0.3,1) both; flex-wrap: wrap; }
  .page-eyebrow { font-size: 0.72rem; font-weight: 700; color: #f97316; letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 0.25rem; }
  .page-title { font-size: 1.65rem; font-weight: 800; color: #fff; margin: 0 0 0.25rem; }
  .page-count { font-size: 0.82rem; color: rgba(255,255,255,0.3); font-weight: 300; }
  .alert-ok { background: rgba(34,197,94,0.09); border: 1px solid rgba(34,197,94,0.22); border-radius: 12px; padding: 0.75rem 1.1rem; margin-bottom: 1.4rem; font-size: 0.83rem; color: #86efac; display: flex; align-items: center; gap: 0.55rem; }
  .alert-error { background: rgba(239,68,68,0.09); border: 1px solid rgba(239,68,68,0.22); border-radius: 12px; padding: 0.8rem 1.1rem; margin-bottom: 1.4rem; font-size: 0.82rem; color: #fca5a5; }

  .layout { display: grid; grid-template-columns: 1fr 320px; gap: 1.25rem; align-items: start; animation: fadeUp 0.4s 0.07s cubic-bezier(0.16,1,0.3,1) both; }

  .table-card { background: rgba(255,255,255,0.025); border: 1px solid rgba(255,255,255,0.07); border-radius: 18px; overflow: hidden; }
  .card-section-title { font-size: 0.68rem; font-weight: 700; color: rgba(255,255,255,0.25); text-transform: uppercase; letter-spacing: 0.1em; padding: 1rem 1.4rem 0.65rem; border-bottom: 1px solid rgba(255,255,255,0.05); }
  .cat-table { width: 100%; border-collapse: collapse; }
  .cat-table thead tr { border-bottom: 1px solid rgba(255,255,255,0.07); }
  .cat-table thead th { padding: 0.75rem 1rem; font-size: 0.68rem; font-weight: 700; color: rgba(255,255,255,0.28); text-transform: uppercase; letter-spacing: 0.08em; }
  .cat-table thead th.center { text-align: center; }
  .cat-table tbody tr { border-bottom: 1px solid rgba(255,255,255,0.04); transition: background 0.15s; }
  .cat-table tbody tr:last-child { border-bottom: none; }
  .cat-table tbody tr:hover { background: rgba(255,255,255,0.03); }
  .cat-table td { padding: 0.85rem 1rem; font-size: 0.85rem; color: rgba(255,255,255,0.7); vertical-align: middle; }
  .cat-table td.center { text-align: center; }
  .id-chip { display: inline-flex; align-items: center; justify-content: center; min-width: 28px; height: 28px; padding: 0 8px; border-radius: 8px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); font-size: 0.72rem; font-weight: 700; color: rgba(255,255,255,0.35); }
  .cat-nombre { font-weight: 700; color: #fff; }
  .cat-desc { font-size: 0.75rem; color: rgba(255,255,255,0.3); margin-top: 0.1rem; }
  .prod-count-badge { display: inline-flex; align-items: center; padding: 0.2rem 0.65rem; border-radius: 999px; font-size: 0.72rem; font-weight: 700; background: rgba(249,115,22,0.1); color: #fdba74; border: 1px solid rgba(249,115,22,0.2); }
  .actions-wrap { display: flex; align-items: center; gap: 0.4rem; justify-content: center; }
  .action-btn { display: inline-flex; align-items: center; gap: 0.3rem; padding: 0.32rem 0.7rem; border-radius: 8px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.73rem; font-weight: 700; cursor: pointer; border: 1px solid transparent; text-decoration: none; transition: all 0.18s; }
  .action-btn.danger { background: rgba(239,68,68,0.1); border-color: rgba(239,68,68,0.2); color: #fca5a5; }
  .action-btn.danger:hover { background: rgba(239,68,68,0.2); border-color: rgba(239,68,68,0.4); transform: translateY(-1px); }

  /* Panel lateral - formulario */
  .side-panel { display: flex; flex-direction: column; gap: 1.1rem; }
  .form-card { background: rgba(255,255,255,0.025); border: 1px solid rgba(255,255,255,0.07); border-radius: 18px; overflow: hidden; }
  .field-group { margin-bottom: 1.1rem; }
  .field-group:last-child { margin-bottom: 0; }
  .field-label { display: block; font-size: 0.72rem; font-weight: 700; color: rgba(255,255,255,0.42); letter-spacing: 0.07em; text-transform: uppercase; margin-bottom: 0.45rem; }
  .field-wrap { position: relative; }
  .field-icon { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); font-size: 0.9rem; color: rgba(255,255,255,0.17); pointer-events: none; transition: color 0.2s; }
  .field-icon.top { top: 14px; transform: none; }
  .field-input, .field-textarea { width: 100%; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; color: #fff; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.88rem; outline: none; transition: border-color 0.2s, background 0.2s, box-shadow 0.2s; }
  .field-input { padding: 0.75rem 0.9rem 0.75rem 2.5rem; }
  .field-textarea { padding: 0.75rem 0.9rem 0.75rem 2.5rem; resize: vertical; min-height: 70px; }
  .field-input::placeholder, .field-textarea::placeholder { color: rgba(255,255,255,0.15); }
  .field-input:focus, .field-textarea:focus { background: rgba(249,115,22,0.06); border-color: rgba(249,115,22,0.5); box-shadow: 0 0 0 3px rgba(249,115,22,0.1); color: #fff; }
  .field-wrap:focus-within .field-icon { color: rgba(249,115,22,0.6); }
  .field-error { font-size: 0.73rem; color: #fca5a5; margin-top: 0.3rem; }
  .btn-submit { width: 100%; padding: 0.78rem; background: linear-gradient(135deg,#f97316,#eab308); border: none; border-radius: 12px; color: #0e0a00; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.88rem; font-weight: 800; cursor: pointer; transition: transform 0.15s, box-shadow 0.2s, opacity 0.2s; box-shadow: 0 5px 18px rgba(249,115,22,0.35); }
  .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 26px rgba(249,115,22,0.45); opacity: 0.93; }

  .empty-state { padding: 3rem 1rem; text-align: center; }
  .empty-icon { font-size: 2.2rem; opacity: 0.35; margin-bottom: 0.6rem; }
  .empty-title { font-size: 0.9rem; font-weight: 700; color: rgba(255,255,255,0.3); margin-bottom: 0.3rem; }
  .empty-sub { font-size: 0.78rem; color: rgba(255,255,255,0.18); }

  @keyframes fadeUp { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: translateY(0); } }
  @media (max-width: 760px) { .layout { grid-template-columns: 1fr; } }
</style>

<div class="page-header">
  <div>
    <p class="page-eyebrow">Administración</p>
    <h1 class="page-title">Categorías</h1>
    <p class="page-count">{{ $categorias->count() }} categoría{{ $categorias->count() !== 1 ? 's' : '' }} registrada{{ $categorias->count() !== 1 ? 's' : '' }}</p>
  </div>
</div>

@if(session('success'))
  <div class="alert-ok">✅ {{ session('success') }}</div>
@endif

@if($errors->any())
  <div class="alert-error">
    <strong>⚠</strong>
    @foreach($errors->all() as $error)<span>{{ $error }}</span>@endforeach
  </div>
@endif

<div class="layout">

  {{-- Tabla de categorías --}}
  <div class="table-card">
    <div class="card-section-title">Lista de categorías</div>
    <div style="overflow-x:auto;">
      <table class="cat-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Categoría</th>
            <th class="center">Productos</th>
            <th class="center">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @forelse($categorias as $categoria)
          <tr>
            <td><span class="id-chip">{{ $categoria->id }}</span></td>
            <td>
              <div class="cat-nombre">{{ $categoria->nombre }}</div>
              @if($categoria->descripcion)
                <div class="cat-desc">{{ $categoria->descripcion }}</div>
              @endif
            </td>
            <td class="center">
              <span class="prod-count-badge">{{ $categoria->productos_count }} productos</span>
            </td>
            <td class="center">
              <div class="actions-wrap">
                <form action="{{ route('categorias.destroy', $categoria) }}" method="POST" class="form-delete">
                  @csrf @method('DELETE')
                  <button type="submit" class="action-btn danger">🗑 Eliminar</button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="4">
              <div class="empty-state">
                <div class="empty-icon">🏷</div>
                <div class="empty-title">Sin categorías</div>
                <div class="empty-sub">Crea la primera desde el formulario</div>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- Formulario lateral --}}
  <div class="side-panel">
    <div class="form-card">
      <div class="card-section-title">Nueva categoría</div>
      <div style="padding:1.25rem 1.4rem;">
        <form method="POST" action="{{ route('categorias.store') }}">
          @csrf
          <div class="field-group">
            <label class="field-label" for="nombre">Nombre</label>
            <div class="field-wrap">
              <input type="text" id="nombre" name="nombre" class="field-input"
                placeholder="Ej. Bebidas, Snacks..."
                value="{{ old('nombre') }}" required>
              <span class="field-icon">🏷</span>
            </div>
            @error('nombre') <div class="field-error">⚠ {{ $message }}</div> @enderror
          </div>
          <div class="field-group">
            <label class="field-label" for="descripcion">Descripción <span style="color:rgba(255,255,255,0.2)">(opcional)</span></label>
            <div class="field-wrap">
              <textarea id="descripcion" name="descripcion" class="field-textarea"
                placeholder="Breve descripción...">{{ old('descripcion') }}</textarea>
              <span class="field-icon top">📝</span>
            </div>
          </div>
          <button type="submit" class="btn-submit">＋ Crear categoría</button>
        </form>
      </div>
    </div>
  </div>

</div>

@endsection
