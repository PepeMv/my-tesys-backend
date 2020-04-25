<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DatosFacturacion;
use Validator;

class DatosFacturacionController extends Controller
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
                'tipoDocumento' => 'required',
                'numeroDocumento' => 'required',
                'nombre' => 'required',
                'direccion' => 'required',
                'telefono' => 'required',
                'idUsuario' => 'required',
                /* 'idUsuario' => 'required', */
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

        $datos = new DatosFacturacion();
        $datos->tipoDocumento = $request->tipoDocumento;
        $datos->numeroDocumento = $request->numeroDocumento;
        $datos->nombre = $request->nombre;
        $datos->direccion = $request->direccion;
        $datos->telefono = $request->telefono;
        $datos->idUsuario = $request->idUsuario;

        $datos->save();

        return response()->json(
            [
                'datos' => $datos,
                'HttpResponse' => [
                    'tittle' => 'Correcto',
                    'message' => 'Nuevo dato de facturación creado!',
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
        $datosFacturacion = DatosFacturacion::where('idUsuario', $id)->get();
        return response()->json(
            [
                'datos' => $datosFacturacion,
                'HttpResponse' => [
                    'status' => 200,
                    'statusText' => 'OK',
                    'ok' => true
                ]
            ],
            201
        );
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

