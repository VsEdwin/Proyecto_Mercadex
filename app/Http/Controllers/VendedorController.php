<?php
namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Producto;
use Illuminate\Support\Facades\Auth;

class VendedorController extends Controller
{
    public function dashboard()
    {
        $ventasHoy      = Venta::whereDate('created_at', today())->count();
        $ingresoHoy     = Venta::whereDate('created_at', today())->sum('total');
        $totalVentas    = Venta::count();
        $totalIngresos  = Venta::sum('total');

        return view('vendedor.dashboard', compact('ventasHoy','ingresoHoy','totalVentas','totalIngresos'));
    }
}