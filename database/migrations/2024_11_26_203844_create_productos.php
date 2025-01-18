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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nombre', length: 100);
            $table->text('observaciones')->nullable();
            $table->text('contacto_proveedores')->nullable();
            $table->date('fecha_aprobacion_oc')->nullable();
            $table->date('fecha_envio_oc')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->date('fecha_entrega_materia')->nullable();
            $table->enum('tipo_de_pago', ['credito','contado','contado y credito']);
            $table->enum('estado_solicitud', ['vigente','terminado'])->default('vigente');
            $table->enum('estado_entrega', ['entrega pendiente', 'entrega total', 'entrega parcial'])->nullable();
            $table->enum('estado_pago', ['pago pendiente', 'pago programado', 'pago liberado'])->nullable();
            $table->enum('estado_oc', [
                'Compra aprobada',
                'Compra corrección',
                'Compra corregida',
                'Compra solicitada',
                'Pendiente OC',
                'Revisión OC',
                'OC liberada',
                'OC por enviar',
                'OC enviada',
                'Cancelada',
                'Rechazada',
                'OC corrección',
                'OC por confirmar',
                'OC confirmada',
                'Revisado OC',
                'OC creada',
                'OC por liberar'
            ])->default('Compra Solicitada');

            $table->enum('estado_cp', [
                'Revisión',
                'Liberado',
                'CP por enviar',
                'CP enviado',
                'Cancelado',
                'No aplica',
                'CP por confirmar',
                'CP confirmado',
                'CP por liberar',
                'Pendiente CP'
            ])->nullable();


            $table->unsignedBigInteger('usuario_solicitante_id'); // ID del solicitante
            $table->unsignedBigInteger('usuario_aprobador_id'); // ID del aprobador, puede ser nulo inicialmente
            $table->unsignedBigInteger('usuario_admin_compra_id'); // ID del generador, puede ser nulo inicialmente
            $table->unsignedBigInteger('usuario_almacenista_id');
            $table->unsignedBigInteger('proyecto_id');
        
            // Relaciones con la tabla de usuarios
            $table->foreign('usuario_solicitante_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('usuario_aprobador_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('usuario_admin_compra_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('usuario_almacenista_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('proyecto_id')->references('id')->on('proyectos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
