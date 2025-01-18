<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enlace extends Model
{
    use HasFactory;
    protected $fillable = ['producto_id', 'rol',  'nombre_original', 'url_sharepoint'];


    public function producto()
    {
        return $this->belongsTo(Productos::class, 'producto_id');
    }
}
