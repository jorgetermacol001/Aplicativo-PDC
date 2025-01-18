<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Productos extends Model
{
    use HasFactory, Notifiable;

    /**
     * La tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'productos';

    /**
     * Los atributos que se pueden asignar de forma masiva.
     *
     * @var array
     */
    protected $fillable = [
        'nombre',
        'proyecto_id',
        'observaciones',
        'tipo_de_pago',
        'estado_entrega',
        'estado_pago',
        'estado_solicitud',
        'fecha_aprobacion_oc',
        'fecha_fin',
        'fecha_envio_oc',
        'fecha_entrega_materia',
        'estado_oc',
        'estado_cp',
        'usuario_solicitante_id',
        'usuario_aprobador_id',
        'contacto_proveedores',
        'usuario_almacenista_id',
        'usuario_admin_compra_id'
        
    ];

    /**
     * Los valores predeterminados para los atributos.
     *
     * @var array
     */
    protected $attributes = [
        'estado_oc' => 'Compra solicitada',
        'estado_solicitud' => 'vigente'
    ];

    /**
     * Relaciones
     */

    // Relación con el usuario que solicitó el producto
    public function solicitante()
    {
        return $this->belongsTo(User::class, 'usuario_solicitante_id');
    }

    // Relación con el usuario que aprobó el producto
    public function aprobador()
    {
        return $this->belongsTo(User::class, 'usuario_aprobador_id');
    }

    // Relación con el usuario Admin compra
    public function adminCompra()
    {
        return $this->belongsTo(User::class, 'usuario_admin_compra_id');
    }

    // Relación con el usuario ALMACENISTA
    public function almacenista()
    {
        return $this->belongsTo(User::class, 'usuario_almacenista_id');
    }
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_id');
    }

    public function enlaces()
    {
        return $this->hasMany(Enlace::class, 'producto_id'); // Especificamos la clave foránea correcta
    }
    

    /**
     * Métodos útiles para trabajar con enumeraciones
     */

    // Obtener los valores posibles de tipo de pago
    public static function getTipoDePagoOptions()
    {
        return ['credito', 'contado', 'contado y credito'];
    }

    public static function getEstadoProyectoOptions(){
        return ['vigente', 'terminado'];
    }

        // Obtener los valores posibles de tipo de pago
        public static function getEstadoPagoOptions()
        {
            return ['pago pendiente', 'pago programado', 'pago liberado'];
        }

    // Obtener los valores posibles de estado de entrega
    public static function getEstadoEntregaOptions()
    {
        return ['entrega pendiente', 'entrega total', 'entrega parcial'];
    }

    // Obtener los valores posibles de estado de OC
    public static function getEstadoOCOptions()
    {
        return [
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
            'OC creada',
            'OC corrección',
            'OC por confirmar',
            'OC confirmada',
            'Revisado OC',
            'OC por liberar'
        ];
    }

    // Obtener los valores posibles de estado de CP
    public static function getEstadoCPOptions()
    {
        return [
            'Revisión',
            'Liberado',
            'CP por enviar',
            'CP enviado',
            'Cancelado',
            'No aplica',
            'CP por confirmar',
            'CP confirmado',
            'CP por liberar',
            'Pendiente CP',
        ];
    }

    protected $casts = [
        'fecha_aprobacion_oc' => 'datetime',
        'fecha_envio_oc' => 'datetime',
        'fecha_fin' => 'datetime',
        'fecha_entrega_materia' => 'datetime'

        
    ];
}
