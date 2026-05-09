<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table    = 'categorias';
    protected $fillable = ['nombre', 'descripcion', 'parent_id'];

    // Sus subcategorías
    public function subcategorias()
    {
        return $this->hasMany(Categoria::class, 'parent_id');
    }

    // Su categoría padre
    public function padre()
    {
        return $this->belongsTo(Categoria::class, 'parent_id');
    }

    // Solo categorías principales (sin padre)
    public function scopePadres($query)
    {
        return $query->whereNull('parent_id');
    }

    public function productos()
    {
        return $this->hasMany(Producto::class, 'categoria_id');
    }
}