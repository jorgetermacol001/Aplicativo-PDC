<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'descripcion', 'user_id, estado',  'fecha_fin', 'estado_proyecto'];

    public function user()
    {
        return $this->belongsTo(User::class); // Un proyecto pertenece a un usuario
    }
    public function productos()
    {
        return $this->hasMany(Productos::class);
    }

    protected $casts = [
        'fecha_fin' => 'datetime'       
    ];
}
