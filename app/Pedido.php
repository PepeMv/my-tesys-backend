<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    public $timestamps = false;
    protected $table = 'pedido';
    protected $casts = [
        'costoEnvio' => 'decimal:2',
        'totalPedido' => 'decimal:2',
    ];
}