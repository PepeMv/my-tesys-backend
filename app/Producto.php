<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    public $timestamps = false;
    protected $table = 'producto';
    protected $casts = [
        'precio' => 'decimal:2',
    ];
    
}