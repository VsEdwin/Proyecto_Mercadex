<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\VendedorController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\CategoriaController;
use Illuminate\Support\Facades\Auth;

Route::middleware('guest')->group(function () {
    Route::get('/', fn() => redirect()->route('login'));
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/home', function () {
        $role = Auth::user()->role;
        return redirect()->route($role . '.dashboard');
    })->name('home');

    // Solo ADMIN
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::resource('productos', ProductoController::class);
        Route::patch('/productos/{producto}/stock', [ProductoController::class, 'updateStock'])->name('productos.updateStock');
        Route::get('/admin/usuarios', [AdminController::class, 'usuarios'])->name('admin.usuarios');
        Route::get('/admin/usuarios', [AdminController::class, 'usuarios'])->name('admin.usuarios');
        Route::get('/admin/usuarios/crear', [AdminController::class, 'createUsuario'])->name('admin.usuarios.create');
        Route::post('/admin/usuarios', [AdminController::class, 'storeUsuario'])->name('admin.usuarios.store');
        Route::delete('/admin/usuarios/{user}', [AdminController::class, 'destroyUsuario'])->name('admin.usuarios.destroy');
        Route::resource('proveedores', ProveedorController::class)
            ->parameters(['proveedores' => 'proveedor']);
        Route::resource('categorias', CategoriaController::class)
            ->parameters(['categorias' => 'categoria']);
    });

    // Solo VENDEDOR
    Route::middleware('role:vendedor')->group(function () {
        Route::get('/vendedor', [VendedorController::class, 'dashboard'])->name('vendedor.dashboard'); // ← cambio
    });

    // ADMIN y VENDEDOR
    Route::middleware('role:admin,vendedor')->group(function () {
        Route::resource('ventas', VentaController::class);
        Route::get('/ventas/{venta}/ticket', [VentaController::class, 'ticket'])->name('ventas.ticket');
    });
});