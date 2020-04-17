<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Restaurante extends Model
{
    public $timestamps = false;
    protected $table = 'restaurante';
    protected $casts = [
        'costoEnvio' => 'decimal:2',
    ];
}
