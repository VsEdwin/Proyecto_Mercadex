<?php
namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index()
    {
        // Solo categorías padre con sus subcategorías
        $categorias = Categoria::padres()
                               ->withCount('productos')
                               ->with('subcategorias')
                               ->get();
        return view('categorias.index', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required']);

        Categoria::create([
            'nombre'      => $request->nombre,
            'descripcion' => $request->descripcion,
            'parent_id'   => $request->parent_id ?? null,
        ]);

        return redirect()->route('categorias.index')
            ->with('success', 'Categoría creada correctamente.');
    }

    public function update(Request $request, Categoria $categoria)
    {
        $request->validate(['nombre' => 'required']);
        $categoria->update([
            'nombre'      => $request->nombre,
            'descripcion' => $request->descripcion,
        ]);
        return redirect()->route('categorias.index')
            ->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy(Categoria $categoria)
    {
        // Elimina también sus subcategorías
        $categoria->subcategorias()->delete();
        $categoria->delete();
        return redirect()->route('categorias.index')
            ->with('success', 'Categoría eliminada.');
    }
}