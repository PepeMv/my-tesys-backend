<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    public $timestamps = false;
    protected $table = 'categoria_producto';
    protected $casts = [
        'activo' => 'int',
    ];
    
}