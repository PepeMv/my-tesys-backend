<?php

namespace App\Http\Controllers;

use App\Producto;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        function getImagenes($productos){
            $array=[];
            foreach($productos as $producto){
               $object = (object)['id' => $producto->id, 'url' => asset('storage/'.$producto->imagen)];
               array_push($array, $object);
            }
            return $array;
        }
        $productos = Producto::all();
        return response()->json(
            [
                'productos' => $productos,
                'imagenes' => getImagenes($productos),
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
                'nombre' => 'required|string',
                'precio' => 'required',
                'idCategoria' => 'required',
                'aplicaiva'=> 'required',
                'imagen'=> 'required|image',
                'descripcion' => 'required|string',
                'activo'=> 'required',
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

        $producto = new Producto();
        $producto->nombre = $request->nombre;
        $producto->precio = str_replace(',','.',$request->precio);
        $producto->idCategoria = $request->idCategoria;
        $producto->aplicaiva = $request->aplicaiva;
        $producto->imagen = '';
        $producto->descripcion = $request->descripcion;
        $producto->activo = $request->activo;
        $producto->save();

        //imagen
        $imagen = $request->file('imagen');
        if($imagen!=null){
            $producto = Producto::find($producto->id);
            $productoImagenName = $imagen->storeAs('imgProductos',$producto->id.'.'.$imagen->getClientOriginalExtension(),'public');
            $producto->imagen = $productoImagenName;
            $producto->save();
        }
        return response()->json(
            [
                'producto' => $producto,                
                'imagen' => [
                    'id' => $producto->id,
                    'url' => asset('storage/'.$producto->imagen)
                ],
                'HttpResponse' => [
                    'tittle' => 'Correcto',
                    'message' => 'Nuevo producto creado!',
                    'status' => 200,
                    'statusText' => 'success',
                    'ok' => true
                ],
            ],
            200
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
        $producto = Producto::find($id);
        if (!$producto) {
            return response()->json([
                'HttpResponse' => [
                    'tittle' => 'Error',
                    'message' => 'No se encontro el producto!',
                    'status' => 400,
                    'statusText' => 'error',
                    'ok' => true
                ]
            ]);
        }

        $validation = Validator::make(
            $request->all(),
            [
                'nombre' => 'required|string',
                'precio' => 'required',
                'idCategoria' => 'required',
                'aplicaiva'=> 'required',
                'descripcion' => 'required|string',
                'activo'=> 'required',
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

        $producto->nombre = $request->nombre;
        $producto->precio = str_replace(',','.',$request->precio);
        $producto->idCategoria = $request->idCategoria;
        $producto->aplicaiva = $request->aplicaiva;        
        $producto->descripcion = $request->descripcion;
        $producto->activo = $request->activo;
        
        $imagen = $request->file('imagen');
        if($imagen!=null){
            Storage::disk('public')->delete($producto->imagen);
            $productoImagenName = $imagen->storeAs('imgProductos',$producto->id.'.'.$imagen->getClientOriginalExtension(),'public');
            $producto->imagen = $productoImagenName;            
        }
        $producto->save();

        return response()->json(
            [
                'producto' => $producto,
                'imagen' => [
                    'id' => $producto->id,
                    'url' => asset('storage/'.$producto->imagen)
                ],
                'HttpResponse' => [
                    'tittle' => 'Correcto',
                    'message' => 'Producto actualizado!',
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
        $producto = Producto::find($id);
        
        if (!$producto) {
            return response()->json([
                'HttpResponse' => [
                    'tittle' => 'Error',
                    'message' => 'No se encontro el producto!',
                    'status' => 400,
                    'statusText' => 'error',
                    'ok' => true
                ]
            ]);
        }

        try {
            Storage::disk('public')->delete($producto->imagen);
            $producto->delete();

            return response()->json([
                'HttpResponse' => [
                    'tittle' => 'Correcto',
                    'message' => 'Producto eliminado!',
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
