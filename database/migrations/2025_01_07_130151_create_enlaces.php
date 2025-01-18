<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('enlaces', function (Blueprint $table) {
            $table->id();
            $table->string('rol'); // Nombre del rol asociado
            $table->string('nombre_original'); // Nombre original del archivo
            $table->string('url_sharepoint')->nullable();
            $table->timestamps();

            $table->unsignedBigInteger('producto_id');
        
            // Relaciones con la tabla de productos
            $table->foreign('producto_id')->references('id')->on('productos')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enlaces');
    }
};
