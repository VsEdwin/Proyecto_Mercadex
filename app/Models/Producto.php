<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = ['nombre', 'descripcion', 'precio', 'stock', 'activo'];

    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }
}