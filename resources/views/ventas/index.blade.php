@extends('layouts.app')
@section('content')

{{-- Header --}}
<div class="page-header">
    <div>
        <p class="page-eyebrow">Registro</p>
        <h1 class="page-title">Lista de Ventas</h1>
        <p class="page-count">{{ $ventas->count() }} venta{{ $ventas->count() !== 1 ? 's' : '' }}
            registrada{{ $ventas->count() !== 1 ? 's' : '' }}</p>
    </div>
    <a href="{{ route('ventas.create') }}" class="btn-new">＋ Nueva Venta</a>
</div>

{{-- Stats rápidas --}}
@unless($ventas->isEmpty())
<div class="stats-row">
    <div class="stat-mini">
        <div class="stat-mini-val orange">{{ $ventas->count() }}</div>
        <div class="stat-mini-label">Total ventas</div>
    </div>
    <div class="stat-mini">
        <div class="stat-mini-val yellow">${{ number_format($ventas->sum('total'), 2) }}</div>
        <div class="stat-mini-label">Ingresos totales</div>
    </div>
    <div class="stat-mini">
        <div class="stat-mini-val">${{ number_format($ventas->avg('total'), 2) }}</div>
        <div class="stat-mini-label">Promedio por venta</div>
    </div>
    <div class="stat-mini">
        <div class="stat-mini-val green">{{ $ventas->where('created_at', '>=', now()->startOfDay())->count() }}</div>
        <div class="stat-mini-label">Ventas hoy</div>
    </div>
</div>
@endunless

{{-- Alert éxito --}}
@if(session('success'))
<div class="alert-ok">✅ {{ session('success') }}</div>
@endif

{{-- Tabla --}}
<div class="table-card">

    @if($ventas->isEmpty())
    <div class="empty-state">
        <div class="empty-icon">🧾</div>
        <div class="empty-title">Sin ventas registradas</div>
        <div class="empty-sub">Registra tu primera venta para verla aquí</div>
        <a href="{{ route('ventas.create') }}" class="btn-empty">＋ Registrar venta</a>
    </div>
    @else
    <div class="table-toolbar">
        <div class="search-wrap">
            <span class="search-icon">🔍</span>
            <input type="text" class="search-input" id="searchInput" placeholder="Buscar por cliente, fecha o total..."
                oninput="filterTable()">
        </div>
        <span class="toolbar-count" id="visibleCount">{{ $ventas->count() }} resultados</span>
    </div>

    <div style="overflow-x: auto;">
        <table class="ventas-table" id="ventasTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Fecha</th>
                    @if(Auth::user()->role === 'admin')
                    <th>Vendedor</th>
                    @endif
                    <th>Cliente</th>
                    <th class="center">Total</th>
                    <th class="center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ventas as $venta)
                <tr>
                    <td><span class="id-chip">{{ $venta->id }}</span></td>
                    <td>
                        <div class="fecha-main">{{ $venta->created_at->format('d/m/Y') }}</div>
                        <div class="fecha-time">{{ $venta->created_at->format('H:i') }} hrs</div>
                    </td>

                    @if(Auth::user()->role === 'admin')
                    <td>
                        <div class="cliente-wrap">
                            <div class="cliente-avatar">
                                {{ strtoupper(substr($venta->user->name ?? 'V', 0, 1)) }}
                            </div>
                            <span class="cliente-name">{{ $venta->user->name ?? 'Desconocido' }}</span>
                        </div>
                    </td>
                    @endif

                    <td>
                        @php $cliente = $venta->cliente ?? 'N/A'; @endphp
                        <div class="cliente-wrap">
                            <div class="cliente-avatar">{{ strtoupper(substr($cliente, 0, 1)) }}</div>
                            <span class="cliente-name">{{ $cliente }}</span>
                        </div>
                    </td>
                    <td class="center">
                        <span class="total-val">${{ number_format($venta->total, 2) }}</span>
                    </td>
                    <td class="center">
                        <div class="actions-wrap">
                            <a href="{{ route('ventas.show', $venta) }}" class="action-btn view">👁 Ver</a>
                            <a href="{{ route('ventas.ticket', $venta) }}" class="action-btn ticket">🧾 Ticket</a>
                            <form action="{{ route('ventas.destroy', $venta) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('¿Eliminar la venta #{{ $venta->id }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="action-btn danger">🗑 Eliminar</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

</div>

<script>
function filterTable() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('#ventasTable tbody tr');
    let visible = 0;

    rows.forEach(row => {
        const show = row.innerText.toLowerCase().includes(q);
        row.style.display = show ? '' : 'none';
        if (show) visible++;
    });

    document.getElementById('visibleCount').textContent =
        visible + ' resultado' + (visible !== 1 ? 's' : '');
}
</script>

@endsection