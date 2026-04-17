<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = ['nombre', 'descripcion', 'precio', 'stock', 'activo','costo','proveedor_id',
    'categoria_id'];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    // Ganancia por unidad
    public function getGananciaAttribute()
    {
        return $this->precio - $this->costo;
    }

    // Ganancia total del stock
    public function getGananciaTotalAttribute()
    {
        return $this->ganancia * $this->stock;
    }

    // Inversión total del stock
    public function getInversionAttribute()
    {
        return $this->costo * $this->stock;
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }
}