@extends('layouts.app')

@section('content')
<div class="container">
  <h3 class="text-success text-center mb-4">🌿 Productos Disponibles</h3>

  @if($productos->isEmpty())
    <div class="alert alert-info text-center">No hay productos disponibles por ahora.</div>
  @else
  <table class="table table-bordered table-hover align-middle shadow-sm">
    <thead class="table-success">
      <tr class="text-center">
        <th>#</th>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Precio (MXN)</th>
        <th>Stock</th>
      </tr>
    </thead>
    <tbody>
      @foreach($productos as $producto)
      <tr>
        <td class="text-center">{{ $producto->id }}</td>
        <td>{{ $producto->nombre }}</td>
        <td>{{ $producto->descripcion }}</td>
        <td class="text-center">${{ number_format($producto->precio, 2) }}</td>
        <td class="text-center">{{ $producto->stock }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
  @endif
</div>
@endsection
