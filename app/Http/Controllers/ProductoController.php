<?php
namespace App\Http\Controllers;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Proveedor;
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
        $categorias  = Categoria::all();
        $proveedores = Proveedor::where('activo', true)->get();
        return view('productos.create', compact('categorias', 'proveedores'));
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
        $categorias  = Categoria::all();
        $proveedores = Proveedor::where('activo', true)->get();
        return view('productos.edit', compact('producto', 'categorias', 'proveedores'));
    }

    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'nombre' => 'required',
            'precio' => 'required|numeric|min:0',
            'stock'  => 'required|integer|min:0',
            'costo'  => 'nullable|numeric|min:0',
        ]);

        $producto->update([
            'nombre'       => $request->nombre,
            'descripcion'  => $request->descripcion,
            'precio'       => $request->precio,
            'costo'        => $request->costo ?? 0,
            'stock'        => $request->stock,
            'categoria_id' => $request->categoria_id,  // ← ¿tienes estas dos líneas?
            'proveedor_id' => $request->proveedor_id,  // ← ¿tienes estas dos líneas?
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