@extends('layouts.app')

@section('content')
<div class="text-center">
  <h2>Bienvenido a Mercadex</h2>
  <p>Consulta los productos disponibles.</p>
  <a href="{{ route('cliente.productos') }}" class="btn btn-outline-warning mt-3">
    Ver productos disponibles
  </a>
</div>
@endsection
