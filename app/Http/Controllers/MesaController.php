<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mesa;
use Validator;

class MesaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(
            [
                'mesas' => Mesa::all(),
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
                'nombre' => 'required',
                'qr' => 'required',
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

        $mesa = new Mesa();        
        $mesa->nombre = $request->nombre;
        $mesa->qr = $request->qr;
        $mesa->descripcion = $request->descripcion;

        $mesa->save();

        return response()->json(
            [
                'mesa' => $mesa,
                'HttpResponse' => [
                    'tittle' => 'Correcto',
                    'message' => 'Nueva mesa creada!',
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
        $mesa = Mesa::find($id);
        if (!$mesa) {
            return response()->json([
                'HttpResponse' => [
                    'tittle' => 'Error',
                    'message' => 'No se encontro la mesa!',
                    'status' => 400,
                    'statusText' => 'error',
                    'ok' => true
                ]
            ]);
        }
      
        $validation = Validator::make(
            $request->all(),
            [
                'nombre' => 'required',
                'descripcion' => 'required'
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

        $mesa->nombre = $request->nombre;        
        $mesa->descripcion = $request->descripcion;
        
        $mesa->save();

        return response()->json(
            [
                'mesa' => $mesa,
                'HttpResponse' => [
                    'tittle' => 'Correcto',
                    'message' => 'Mesa actualizada!',
                    'status' => 200,
                    'statusText' => 'success',
                    'ok' => true
                ],
            ],
            201
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $mesa = Mesa::find($id);

        if (!$mesa) {
            return response()->json([
                'HttpResponse' => [
                    'tittle' => 'Error',
                    'message' => 'No se encontro la mesa!',
                    'status' => 400,
                    'statusText' => 'error',
                    'ok' => true
                ]
            ]);
        }

        try {
            $mesa->delete();
            return response()->json([
                'HttpResponse' => [
                    'tittle' => 'Correcto',
                    'message' => 'Mesa eliminada!',
                    'status' => 200,
                    'statusText' => 'success',
                    'ok' => true
                ],
            ]);
        } catch (Exception $e) {
            return response()->json([
                'HttpResponse' => [
                    'tittle' => 'Error',
                    'message' => 'Algo salio mal, intende nuevamente!',
                    'status' => 400,
                    'statusText' => 'error',
                    'ok' => true
                ]
            ]);
        }
    }
}
