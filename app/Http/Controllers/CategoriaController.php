<?php

namespace App\Http\Controllers;

use App\Categoria;
use Illuminate\Http\Request;
use Validator;

class CategoriaController extends Controller
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
                'categorias' => Categoria::all(),
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
                'nombre' => 'required|string',
                'activo' => 'required',
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

        $categoria = new Categoria();
        $categoria->nombre = $request->nombre;
        $categoria->activo = $request->activo;

        $categoria->save();

        return response()->json(
            [
                'categoria' => $categoria,
                'HttpResponse' => [
                    'tittle' => 'Correcto',
                    'message' => 'Nueva categoría creada!',
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
        $categoria = Categoria::find($id);
        if (!$categoria) {
            return response()->json([
                'HttpResponse' => [
                    'tittle' => 'Error',
                    'message' => 'No se encontro la categoría!',
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
                'activo' => 'required'
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

        $categoria->nombre = $request->nombre;
        
        $categoria->activo = $request->activo;
        

        $categoria->save();

        return response()->json(
            [
                'categoria' => $categoria,
                'HttpResponse' => [
                    'tittle' => 'Correcto',
                    'message' => 'Categoría actualizada!',
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
        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json([
                'HttpResponse' => [
                    'tittle' => 'Error',
                    'message' => 'No se encontro la categoría!',
                    'status' => 400,
                    'statusText' => 'error',
                    'ok' => true
                ]
            ]);
        }

        try {
            $categoria->delete();

            return response()->json([
                'HttpResponse' => [
                    'tittle' => 'Correcto',
                    'message' => 'Categoría eliminada!',
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
