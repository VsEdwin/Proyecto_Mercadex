<?php
namespace App\Http\Controllers;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::where('activo', true)->get();
        return view('productos.index', compact('productos'));
    }

    public function create()
    {
        return view('productos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'precio' => 'required|numeric|min:0',
            'stock'  => 'required|integer|min:0',
        ]);

        Producto::create([
            'nombre'      => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio'      => $request->precio,
            'stock'       => $request->stock,
            'activo'      => true,
        ]);

        return redirect()->route('productos.index')
            ->with('success', 'Producto creado correctamente');
    }

    public function edit(Producto $producto)
    {
        return view('productos.edit', compact('producto'));
    }

    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'nombre' => 'required',
            'precio' => 'required|numeric|min:0',
            'stock'  => 'required|integer|min:0',
        ]);

        $producto->update([
            'nombre'      => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio'      => $request->precio,
            'stock'       => $request->stock,
        ]);

        return redirect()->route('productos.index')
            ->with('success', 'Producto actualizado correctamente');
    }

    // ← NUEVO: actualizar solo el stock desde la tabla
    public function updateStock(Request $request, Producto $producto)
    {
        $request->validate([
            'stock' => 'required|integer|min:0',
        ]);

        $producto->update(['stock' => $request->stock]);

        return back()->with('success', "Stock de «{$producto->nombre}» actualizado a {$request->stock} unidades.");
    }

    public function destroy(Producto $producto)
    {
        $producto->update(['activo' => false]);
        return redirect()->route('productos.index')
            ->with('success', 'Producto eliminado correctamente');
    }

    public function disponibles()
    {
        $productos = Producto::where('activo', true)
                             ->where('stock', '>', 0)
                             ->get();
        return view('cliente.productos', compact('productos'));
    }

    public function inactivos()
    {
        $productos = Producto::where('activo', false)->get();
        return view('productos.inactivos', compact('productos'));
    }
}