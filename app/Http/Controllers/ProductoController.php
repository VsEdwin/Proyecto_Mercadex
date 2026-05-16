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
        $categorias  = Categoria::padres()->with('subcategorias')->get();
        $proveedores = Proveedor::where('activo', true)->get();
        return view('productos.create', compact('categorias', 'proveedores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'                   => 'required',
            'precio'                   => 'required|numeric|min:0',
            'stock'                    => 'required|integer|min:0',
            'costo'                    => 'nullable|numeric|min:0',
            'presentacion'             => 'required|in:unidad,caja,paquete',
            'unidades_por_presentacion'=> 'required|integer|min:1',
        ]);

        // Si compras 3 cajas de 24 unidades → stock real = 72
        $unidadesPorPresentacion = $request->unidades_por_presentacion;
        $stockReal = $request->stock * $unidadesPorPresentacion;

        Producto::create([
            'nombre'                    => $request->nombre,
            'descripcion'               => $request->descripcion,
            'precio'                    => $request->precio,
            'costo'                     => $request->costo ?? 0,
            'stock'                     => $stockReal, // ← stock en unidades reales
            'activo'                    => true,
            'categoria_id'              => $request->categoria_id,
            'subcategoria_id'           => $request->subcategoria_id,
            'proveedor_id'              => $request->proveedor_id,
            'presentacion'              => $request->presentacion,
            'unidades_por_presentacion' => $unidadesPorPresentacion,
        ]);

        return redirect()->route('productos.index')
            ->with('success', 'Producto creado correctamente.');
    }

    public function edit(Producto $producto)
    {
        $categorias  = Categoria::padres()->with('subcategorias')->get();
        $proveedores = Proveedor::where('activo', true)->get();
        return view('productos.edit', compact('producto', 'categorias', 'proveedores'));
    }

    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'nombre'                    => 'required',
            'precio'                    => 'required|numeric|min:0',
            'stock'                     => 'required|integer|min:0',
            'costo'                     => 'nullable|numeric|min:0',
            'presentacion'              => 'required|in:unidad,caja,paquete',
            'unidades_por_presentacion' => 'required|integer|min:1',
        ]);

        $unidadesPorPresentacion = $request->unidades_por_presentacion;
        $stockReal = $request->stock * $unidadesPorPresentacion;

        $producto->update([
            'nombre'                    => $request->nombre,
            'descripcion'               => $request->descripcion,
            'precio'                    => $request->precio,
            'costo'                     => $request->costo ?? 0,
            'stock'                     => $stockReal,
            'categoria_id'              => $request->categoria_id,
            'subcategoria_id'           => $request->subcategoria_id,
            'proveedor_id'              => $request->proveedor_id,
            'presentacion'              => $request->presentacion,
            'unidades_por_presentacion' => $unidadesPorPresentacion,
        ]);

        return redirect()->route('productos.index')
            ->with('success', 'Producto actualizado correctamente.');
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