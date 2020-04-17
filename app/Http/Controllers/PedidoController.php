<?php

namespace App\Http\Controllers;

use App\Pedido;
use DB;
use App\DetallePedido;
use Illuminate\Http\Request;
use Validator;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = Validator::make(
            $request->all(),
            [   
                'idUsuario' => 'required',
                'entregarPedido' => 'required',
                'totalPedido' => 'required',
                'tipoPedido' => 'required',
                'productos' => 'required',
                'idDatosFacturacion' => 'required',
                'nombreCliente' => 'required',
                'numeroDocumento' => 'required',
                'costoEnvio' => 'required',
                'iva' => 'required',
            ]
        );

        if ($validation->fails()) {
            return response()->json(
                [
                    'HttpResponse' => [
                        'tittle' => 'Error',
                        'message' => 'No hay parametros correctos!',
                        'status' => 400,
                        'statusText' => 'error',
                        'ok' => true
                    ]
                ]
            );
        }
        
        $pedido = new Pedido();
        $pedido->fechahoraPedido = date("Y-m-d H:i:s");
        $pedido->numeroPedido = rand(5, 9999).'-'.date("Ymd");
        $pedido->idUsuario = $request->idUsuario;
        $pedido->entregarPedido = $request->entregarPedido;
        $pedido->totalPedido = $request->totalPedido;
        $pedido->tipoPedido = $request->tipoPedido;
        $pedido->idDatosFacturacion = $request->idDatosFacturacion;
        $pedido->nombreCliente = $request->nombreCliente;
        $pedido->numeroDocumento = $request->numeroDocumento;
        $pedido->costoEnvio = $request->costoEnvio;
        $pedido->estado = "pedido";
        $pedido->iva = $request->iva;
        $pedido->save();
        

        $items = json_decode($request->productos, true);
        //print_r($items);
        //print_r(sizeof($items));
        foreach ($items as $item) {
            
            $idProducto = $item['producto']['id'];
            $nombreProducto = $item['producto']['nombre'];
            $precioProducto = $item['producto']['precio'];
            $cantidad = $item['cantidad'];
            $subtotalItem = $item['preciototal'];
            
            $detalle = new DetallePedido;
            $detalle->idPedido = $pedido->id;
            $detalle->idProducto = $idProducto;
            $detalle->nombreProducto = $nombreProducto;
            $detalle->precioProducto = $precioProducto;
            $detalle->cantidadProducto = $cantidad;
            $detalle->subtotalDetalle = $subtotalItem;
            $detalle->save();
        }
        
        return response()->json(
            [
                'pedido' => $pedido,
                'detalles' => DB::table('detalle_pedido')->where('idPedido','=',$pedido->id)->get(),
                'HttpResponse' => [
                    'tittle' => 'Correcto',
                    'message' => 'Pedido generado!',
                    'status' => 200,
                    'statusText' => 'success',
                    'ok' => true
                ],
            ],
            201
        );
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
