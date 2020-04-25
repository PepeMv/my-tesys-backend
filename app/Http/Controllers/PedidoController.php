<?php

namespace App\Http\Controllers;

use App\Pedido;
use App\User;
use App\Events\PedidoPreparado;
use App\Events\NuevoPedido;
use App\Events\PedidoCancelado;
use App\Events\PedidoEntregado;
use App\Notifications\InvoicePaid;
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
                'idEntrega' => 'required',
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
        $pedido->idEntrega = $request->idEntrega;
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
        $pedido = Pedido::find($pedido->id);

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

        $detalles = DB::table('detalle_pedido')->where('idPedido','=',$pedido->id)->get();

        //disparo el evento 
        //echo($detalles);
        event(new NuevoPedido($pedido, $detalles));

        return response()->json(
            [
                'pedido' => $pedido,
                'detalles' => $detalles,
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

    public function getPedidosParaPreparar (){

        $pedidos = DB::table('pedido')->where('estado','=','pedido')->get();
        $detalles = array();
        foreach ($pedidos as $pedido) {
            $detalleTemporal = DB::table('detalle_pedido')->where('idPedido','=',$pedido->id)->get();
            foreach ($detalleTemporal as $item) {
                array_push($detalles, $item);
            }
        }

        return response()->json(
            [
                'pedidos' => $pedidos,
                'detalles' => $detalles,
                'HttpResponse' => [
                    'status' => 200,
                    'statusText' => 'OK',
                    'ok' => true
                ]
            ],
            201
        );
    }

    public function getPedidosParaEntregar (){

        $pedidos = DB::table('pedido')->where('estado','=','preparado')->get();
        $detalles = array();
        foreach ($pedidos as $pedido) {
            $detalleTemporal = DB::table('detalle_pedido')->where('idPedido','=',$pedido->id)->get();
            foreach ($detalleTemporal as $item) {
                array_push($detalles, $item);
            }
        }

        return response()->json(
            [
                'pedidos' => $pedidos,
                'detalles' => $detalles,
                'HttpResponse' => [
                    'status' => 200,
                    'statusText' => 'OK',
                    'ok' => true
                ]
            ],
            201
        );
    }


    public function getPedidosByUsuario($id){
        $pedidos = Pedido::where('idUsuario', $id)->orderBy('fechahoraPedido', 'desc')->get();
        //consulto los detalles de cada pedido 
        $detalles = array();
        foreach ($pedidos as $pedido) {
            $detalleTemporal = DB::table('detalle_pedido')->where('idPedido','=',$pedido->id)->get();
            foreach ($detalleTemporal as $item) {
                array_push($detalles, $item);
            }
        }
        return response()->json(
            [
                'pedidos' => $pedidos,
                'detalles' => $detalles,
                'HttpResponse' => [
                    'status' => 200,
                    'statusText' => 'OK',
                    'ok' => true
                ]
            ],
            201
        );
    }

    public function getPedidosyDetallesGeneral (){
        $pedidos = Pedido::all();
        $detalles = DetallePedido::all();
        return response()->json(
            [
                'pedidos' => $pedidos,
                'detalles' => $detalles,
                'HttpResponse' => [
                    'status' => 200,
                    'statusText' => 'OK',
                    'ok' => true
                ]
            ],
            201
        );
    }

    public function actualizarEstadoPedido(Request $request, $id){
        $pedido = Pedido::find($id);
        if (!$pedido) {
            return response()->json([
                'HttpResponse' => [
                    'tittle' => 'Error',
                    'message' => 'No se encontro el pedido!',
                    'status' => 400,
                    'statusText' => 'error',
                    'ok' => true
                ]
            ]);
        }

        $validation = Validator::make(
            $request->all(),
            [
                'estado' => 'required',
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

        $pedido->estado = $request->estado;
        $pedido->save();

        $detalles = array();
        $detalleTemporal = DB::table('detalle_pedido')->where('idPedido','=',$pedido->id)->get();
        foreach ($detalleTemporal as $item) {
            array_push($detalles, $item);
        }

        if($request->estado == 'preparado'){
            event(new PedidoPreparado($pedido, $detalles));
            //notificar de esta preparado
            $user = User::find($pedido->idUsuario);
            $user->notify(new InvoicePaid($pedido->id));

        } else if($request->estado == 'cancelado'){
            //lanzar evento para eliminar de los states locales de cada admin
            event(new PedidoCancelado($pedido, $detalles));
            //notificar de cancelacion ?
        } else if($request->estado == 'entregado'){
            event(new PedidoEntregado($pedido, $detalles));
            //notificar de la entrega
        }

        return response()->json(
            [
                //'pedido' => $pedido,
                //'detalles' => $detalles,
                'HttpResponse' => [
                    'tittle' => 'Correcto',
                    'message' => 'Pedido actualizado!',
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
