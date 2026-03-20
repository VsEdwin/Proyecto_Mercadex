<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('detalle_ventas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('venta_id')->constrained();
        $table->foreignId('producto_id')->constrained();
        $table->integer('cantidad');
        $table->decimal('subtotal', 8, 2);
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_ventas');
    }
};
