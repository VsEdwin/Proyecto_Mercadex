@extends('layouts.app')
@section('content')

<style>
  .page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 1.75rem;
    animation: fadeUp 0.4s cubic-bezier(0.16,1,0.3,1) both;
    flex-wrap: wrap;
  }

  .page-eyebrow {
    font-size: 0.72rem;
    font-weight: 700;
    color: #f97316;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    margin-bottom: 0.25rem;
  }

  .page-title {
    font-size: 1.65rem;
    font-weight: 800;
    color: #fff;
    margin: 0 0 0.25rem;
  }

  .page-count {
    font-size: 0.82rem;
    color: rgba(255,255,255,0.3);
    font-weight: 300;
  }

  .btn-new {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    padding: 0.65rem 1.2rem;
    background: linear-gradient(135deg, #f97316, #eab308);
    border: none;
    border-radius: 12px;
    color: #0e0a00;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.85rem;
    font-weight: 800;
    text-decoration: none;
    transition: transform 0.15s, box-shadow 0.2s, opacity 0.2s;
    box-shadow: 0 4px 16px rgba(249,115,22,0.35);
    white-space: nowrap;
    align-self: center;
  }

  .btn-new:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(249,115,22,0.45);
    opacity: 0.93;
    color: #0e0a00;
  }

  /* Stats */
  .stats-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 0.85rem;
    margin-bottom: 1.5rem;
    animation: fadeUp 0.4s 0.06s cubic-bezier(0.16,1,0.3,1) both;
  }

  .stat-mini {
    background: rgba(255,255,255,0.025);
    border: 1px solid rgba(255,255,255,0.07);
    border-radius: 14px;
    padding: 1rem 1.2rem;
    transition: border-color 0.2s;
  }

  .stat-mini:hover { border-color: rgba(249,115,22,0.25); }

  .stat-mini-val {
    font-size: 1.45rem;
    font-weight: 800;
    color: #fff;
    line-height: 1;
    margin-bottom: 0.25rem;
  }

  .stat-mini-val.orange { color: #f97316; }
  .stat-mini-val.yellow { color: #eab308; }

  .stat-mini-label {
    font-size: 0.7rem;
    color: rgba(255,255,255,0.28);
    text-transform: uppercase;
    letter-spacing: 0.07em;
    font-weight: 600;
  }

  /* Alert */
  .alert-ok {
    background: rgba(34,197,94,0.09);
    border: 1px solid rgba(34,197,94,0.22);
    border-radius: 12px;
    padding: 0.75rem 1.1rem;
    margin-bottom: 1.4rem;
    font-size: 0.83rem;
    color: #86efac;
    display: flex;
    align-items: center;
    gap: 0.55rem;
    animation: fadeUp 0.4s cubic-bezier(0.16,1,0.3,1) both;
  }

  /* Tabla */
  .table-card {
    background: rgba(255,255,255,0.025);
    border: 1px solid rgba(255,255,255,0.07);
    border-radius: 18px;
    overflow: hidden;
    animation: fadeUp 0.4s 0.1s cubic-bezier(0.16,1,0.3,1) both;
  }

  .table-toolbar {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid rgba(255,255,255,0.06);
    flex-wrap: wrap;
  }

  .search-wrap { position: relative; flex: 1; min-width: 180px; }

  .search-icon {
    position: absolute;
    left: 12px; top: 50%;
    transform: translateY(-50%);
    font-size: 0.85rem;
    color: rgba(255,255,255,0.2);
    pointer-events: none;
  }

  .search-input {
    width: 100%;
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 10px;
    color: #fff;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.83rem;
    padding: 0.55rem 0.9rem 0.55rem 2.3rem;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
  }

  .search-input::placeholder { color: rgba(255,255,255,0.18); }
  .search-input:focus {
    border-color: rgba(249,115,22,0.45);
    box-shadow: 0 0 0 3px rgba(249,115,22,0.1);
  }

  .toolbar-count {
    font-size: 0.75rem;
    color: rgba(255,255,255,0.25);
    white-space: nowrap;
  }

  .users-table { width: 100%; border-collapse: collapse; }

  .users-table thead tr { border-bottom: 1px solid rgba(255,255,255,0.07); }

  .users-table thead th {
    padding: 0.75rem 1rem;
    font-size: 0.68rem;
    font-weight: 700;
    color: rgba(255,255,255,0.28);
    text-transform: uppercase;
    letter-spacing: 0.08em;
    white-space: nowrap;
  }

  .users-table thead th.center { text-align: center; }

  .users-table tbody tr {
    border-bottom: 1px solid rgba(255,255,255,0.04);
    transition: background 0.15s;
  }

  .users-table tbody tr:last-child { border-bottom: none; }
  .users-table tbody tr:hover { background: rgba(255,255,255,0.03); }

  .users-table td {
    padding: 0.9rem 1rem;
    font-size: 0.85rem;
    color: rgba(255,255,255,0.7);
    vertical-align: middle;
  }

  .users-table td.center { text-align: center; }

  /* ID */
  .id-chip {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 28px; height: 28px;
    padding: 0 8px;
    border-radius: 8px;
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.08);
    font-size: 0.72rem;
    font-weight: 700;
    color: rgba(255,255,255,0.35);
  }

  /* Usuario */
  .user-wrap {
    display: flex;
    align-items: center;
    gap: 0.7rem;
  }

  .user-avatar {
    width: 34px; height: 34px;
    border-radius: 50%;
    background: linear-gradient(135deg, rgba(249,115,22,0.3), rgba(234,179,8,0.3));
    border: 1px solid rgba(249,115,22,0.25);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    font-weight: 800;
    color: #f97316;
    flex-shrink: 0;
    text-transform: uppercase;
  }

  .user-name {
    font-weight: 700;
    color: #fff;
    font-size: 0.88rem;
  }

  .user-email {
    font-size: 0.75rem;
    color: rgba(255,255,255,0.28);
    margin-top: 0.1rem;
  }

  /* Role badge */
  .role-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.22rem 0.7rem;
    border-radius: 999px;
    font-size: 0.72rem;
    font-weight: 700;
  }

  .role-badge.admin {
    background: rgba(239,68,68,0.1);
    color: #fca5a5;
    border: 1px solid rgba(239,68,68,0.2);
  }

  .role-badge.vendedor {
    background: rgba(249,115,22,0.1);
    color: #fdba74;
    border: 1px solid rgba(249,115,22,0.2);
  }

  /* Fecha */
  .fecha-val {
    font-size: 0.82rem;
    color: rgba(255,255,255,0.4);
  }

  /* Acciones */
  .action-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.32rem 0.7rem;
    border-radius: 8px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.73rem;
    font-weight: 700;
    cursor: pointer;
    border: 1px solid transparent;
    text-decoration: none;
    transition: all 0.18s;
  }

  .action-btn.danger {
    background: rgba(239,68,68,0.1);
    border-color: rgba(239,68,68,0.2);
    color: #fca5a5;
  }

  .action-btn.danger:hover {
    background: rgba(239,68,68,0.2);
    border-color: rgba(239,68,68,0.4);
    color: #fca5a5;
    transform: translateY(-1px);
  }

  .action-btn.warning {
    background: rgba(249,115,22,0.1);
    border-color: rgba(249,115,22,0.2);
    color: #fdba74;
  }

  .action-btn.warning:hover {
    background: rgba(249,115,22,0.2);
    border-color: rgba(249,115,22,0.4);
    color: #fdba74;
    transform: translateY(-1px);
  }

  .self-badge {
    font-size: 0.7rem;
    color: rgba(255,255,255,0.2);
    font-style: italic;
  }

  /* Empty */
  .empty-state {
    padding: 3.5rem 1rem;
    text-align: center;
  }

  .empty-icon { font-size: 2.5rem; opacity: 0.35; margin-bottom: 0.75rem; }
  .empty-title { font-size: 0.95rem; font-weight: 700; color: rgba(255,255,255,0.3); margin-bottom: 0.4rem; }
  .empty-sub { font-size: 0.8rem; color: rgba(255,255,255,0.18); }

  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(14px); }
    to   { opacity: 1; transform: translateY(0); }
  }
</style>

{{-- Header --}}
<div class="page-header">
  <div>
    <p class="page-eyebrow">Administración</p>
    <h1 class="page-title">Usuarios del sistema</h1>
    <p class="page-count">{{ $usuarios->count() }} usuario{{ $usuarios->count() !== 1 ? 's' : '' }} registrado{{ $usuarios->count() !== 1 ? 's' : '' }}</p>
  </div>
  <a href="{{ route('admin.usuarios.create') }}" class="btn-new">Nuevo Vendedor</a>
</div>

{{-- Stats --}}
<div class="stats-row">
  <div class="stat-mini">
    <div class="stat-mini-val orange">{{ $usuarios->count() }}</div>
    <div class="stat-mini-label">Total usuarios</div>
  </div>
  <div class="stat-mini">
    <div class="stat-mini-val yellow">{{ $usuarios->where('role', 'vendedor')->count() }}</div>
    <div class="stat-mini-label">Vendedores</div>
  </div>
  <div class="stat-mini">
    <div class="stat-mini-val">{{ $usuarios->where('role', 'admin')->count() }}</div>
    <div class="stat-mini-label">Admins</div>
  </div>
</div>

@if(session('success'))
  <div class="alert-ok">✅ {{ session('success') }}</div>
@endif

{{-- Tabla --}}
<div class="table-card">
  <div class="table-toolbar">
    <div class="search-wrap">
      <span class="search-icon">🔍</span>
      <input type="text" class="search-input" id="searchInput"
        placeholder="Buscar por nombre o correo..."
        oninput="filterTable()">
    </div>
    <span class="toolbar-count" id="visibleCount">{{ $usuarios->count() }} resultados</span>
  </div>

  <div style="overflow-x: auto;">
    <table class="users-table" id="usersTable">
      <thead>
        <tr>
          <th>#</th>
          <th>Usuario</th>
          <th class="center">Rol</th>
          <th class="center">Registro</th>
          <th class="center">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($usuarios as $usuario)
        <tr>
          <td><span class="id-chip">{{ $usuario->id }}</span></td>
          <td>
            <div class="user-wrap">
              <div class="user-avatar">{{ strtoupper(substr($usuario->name, 0, 1)) }}</div>
              <div>
                <div class="user-name">{{ $usuario->name }}</div>
                <div class="user-email">{{ $usuario->email }}</div>
              </div>
            </div>
          </td>
          <td class="center">
            <span class="role-badge {{ $usuario->role }}">
              {{ $usuario->role === 'admin' ? '⚡' : '🏷' }} {{ ucfirst($usuario->role) }}
            </span>
          </td>
          <td class="center">
            <span class="fecha-val">{{ $usuario->created_at->format('d/m/Y') }}</span>
          </td>
          <td class="center">
            @if($usuario->id === Auth::id())
              <span class="self-badge">Tú mismo</span>
            @else
              <form action="{{ route('admin.usuarios.destroy', $usuario) }}" method="POST" class="form-delete">
                @csrf @method('DELETE')
                <button type="submit" class="action-btn danger">🗑 Eliminar</button>
              </form>
            @endif
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5">
            <div class="empty-state">
              <div class="empty-icon">👥</div>
              <div class="empty-title">Sin usuarios registrados</div>
              <div class="empty-sub">Agrega el primer vendedor con el botón de arriba</div>
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
    const rows = document.querySelectorAll('#usersTable tbody tr');
    let visible = 0;
    rows.forEach(row => {
      const show = row.innerText.toLowerCase().includes(q);
      row.style.display = show ? '' : 'none';
      if (show) visible++;
    });
    document.getElementById('visibleCount').textContent = visible + ' resultado' + (visible !== 1 ? 's' : '');
  }
</script>

@endsection