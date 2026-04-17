<?php
namespace App\Http\Controllers;
use App\Models\Producto;
use App\Models\Venta;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalProductos = Producto::where('activo', true)->count();
        $totalVentas    = Venta::count();
        $totalUsuarios  = User::count();
        $totalIngresos  = Venta::sum('total');
        $ventasHoy      = Venta::whereDate('created_at', today())->count();

        // ← Nuevos datos financieros
        $totalInversion  = Producto::where('activo', true)
                                ->selectRaw('SUM(costo * stock) as total')
                                ->value('total') ?? 0;

        $totalGanancia   = Producto::where('activo', true)
                                ->selectRaw('SUM((precio - costo) * stock) as total')
                                ->value('total') ?? 0;

        return view('admin.dashboard',
        compact('totalProductos','totalVentas','totalUsuarios','totalIngresos','ventasHoy',
        'totalInversion', 'totalGanancia'));
    }
    public function usuarios()
    {
        $usuarios = User::orderBy('created_at', 'desc')->get();
        return view('admin.usuarios', compact('usuarios'));
    }
        // Mostrar formulario
    public function createUsuario()
    {
        return view('admin.usuarios-create');
    }

    // Guardar nuevo vendedor
    public function storeUsuario(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'vendedor',
        ]);

        return redirect()->route('admin.usuarios')
            ->with('success', "Vendedor {$request->name} creado correctamente.");
    }

    // Eliminar usuario
    public function destroyUsuario(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->withErrors(['error' => 'No puedes eliminarte a ti mismo.']);
        }
        $user->delete();
        return redirect()->route('admin.usuarios')
            ->with('success', "Usuario {$user->name} eliminado.");
    }
}