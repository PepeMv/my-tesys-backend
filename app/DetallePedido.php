<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    public $timestamps = false;
    protected $table = 'detalle_pedido';
    protected $casts = [
        'precioProducto' => 'decimal:2',
        'subtotalDetalle' => 'decimal:2',
    ];
}