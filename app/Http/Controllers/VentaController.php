<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\{Venta, DetalleVenta, Producto};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Admin ve todas, vendedor solo las suyas
        if ($user->role === 'admin') {
            $ventas = Venta::with('user')->orderBy('created_at', 'desc')->get();
        } else {
            $ventas = Venta::with('user')
                        ->where('user_id', $user->id)
                        ->orderBy('created_at', 'desc')
                        ->get();
        }

        return view('ventas.index', compact('ventas'));
    }

    public function create()
    {
        $productos = Producto::where('activo', true)
                            ->where('stock', '>', 0)
                            ->get();

        return view('ventas.create', compact('productos'));
    }


    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $total = 0;

            // 1️⃣ Validar stock
            foreach ($request->producto_id as $key => $id) {
                $cantidad = (int) $request->cantidad[$key];

                if ($cantidad <= 0) {
                    continue;
                }

                $producto = Producto::findOrFail($id);

                if ($cantidad > $producto->stock) {
                    return back()->withErrors([
                        'stock' => "No hay suficiente stock de {$producto->nombre}"
                    ])->withInput();
                }

                $total += $cantidad * $producto->precio;
            }

            // 2️⃣ Crear venta
            $venta = Venta::create([
                'user_id' => Auth::id(),
                'total' => $total,
                'estado' => 'completada'
            ]);

            // 3️⃣ Crear detalles y reducir stock
            foreach ($request->producto_id as $key => $id) {
                $cantidad = (int) $request->cantidad[$key];

                if ($cantidad <= 0) {
                    continue;
                }

                $producto = Producto::findOrFail($id);

                DetalleVenta::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $id,
                    'cantidad' => $cantidad,
                    'subtotal' => $cantidad * $producto->precio
                ]);

                // 🔻 Reducir stock
                $producto->decrement('stock', $cantidad);
            }

            DB::commit();

            return redirect()->route('ventas.index')
                ->with('success', 'Venta registrada y stock actualizado');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al registrar la venta']);
        }
    }

    public function show(Venta $venta){
        $venta->load('detalles.producto');
        return view('ventas.ticket', compact('venta'));
    }
    
    public function ticket(Venta $venta)
    {
        return view('ventas.ticket', compact('venta'));
    }

    public function destroy(Venta $venta)
    {
        DB::beginTransaction();

        try {
            // 🔄 Regresar stock de los productos vendidos
            foreach ($venta->detalles as $detalle) {
                $producto = Producto::find($detalle->producto_id);
                if ($producto) {
                    $producto->increment('stock', $detalle->cantidad);
                }
            }

            // ❌ Eliminar detalles y venta
            $venta->detalles()->delete();
            $venta->delete();

            DB::commit();

            return redirect()->route('ventas.index')
                ->with('success', 'Venta eliminada y stock restaurado');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors([
                'error' => 'No se pudo eliminar la venta'
            ]);
        }
    }
}
