<?php

namespace App\Http\Controllers;

use App\Restaurante;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Storage;

class RestauranteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $restaurante = Restaurante::first();
        return response()->json(
            [
                'data' => [
                    'nombre' =>  $restaurante->nombre,
                    'estado' =>  $restaurante->estado,
                    'iva' =>  $restaurante->iva,
                    'id' =>   $restaurante->id,
                ],
                'imagenes' => [
                    'logo'=> asset('storage/'.$restaurante->logo),
                    'img1'=> asset('storage/'.$restaurante->img1),
                    'img2'=> asset('storage/'.$restaurante->img2),
                    'img3'=> asset('storage/'.$restaurante->img3),
                    'img4'=> asset('storage/'.$restaurante->img4),
                ],
                'HttpResponse' => [
                    'status' => 200,
                    'statusText' => 'success',
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
    public function update(Request $request, $id)
    {        
        $validation = Validator::make(
            $request->all(),
            [
                'nombre' => 'required|string',
                'estado' => 'required',
                'iva' => 'required',
                /* 'idUsuario' => 'required', */
            ]
        );
        if ($validation->fails()) {
            return response()->json(
                [
                    'HttpResponse' => [
                    'tittle' => 'Error',
                    'message' => 'Datos del restaurante no actualizados',
                    'status' => 400,
                    'statusText' => 'error',
                    'ok' => true
                    ]
                ]
            );
        }
        //reviso si el usuario es de tipo usuario
        


        $restaurante = Restaurante::find($id);
        $restaurante->nombre = $request->nombre;
        $restaurante->estado = $request->estado;
        $restaurante->iva = $request->iva;
        //$restaurante->save();

        $logo = $request->file('logo');
        if ($logo != null) {
            Storage::disk('public')->delete($restaurante->logo);
            $logoName = $logo->storeAs('imgRestaurante','logo.'.$logo->getClientOriginalExtension(),'public');
            $restaurante->logo = $logoName;            
        }
        $img1 = $request->file('img1');
        if ($img1 != null) {
            Storage::disk('public')->delete($restaurante->img1);
            $img1Name = $img1->storeAs('imgRestaurante','img1.'.$img1->getClientOriginalExtension(),'public');
            $restaurante->img1 = $img1Name;            
        }
        $img2 = $request->file('img2');
        if ($img2 != null) {
            Storage::disk('public')->delete($restaurante->img2);
            $img2Name = $img2->storeAs('imgRestaurante','img2.'.$img2->getClientOriginalExtension(),'public');
            $restaurante->img2 = $img2Name;            
        }
        $img3 = $request->file('img3');
        if ($img3 != null) {
            Storage::disk('public')->delete($restaurante->img3);
            $img3Name = $img3->storeAs('imgRestaurante','img3.'.$img3->getClientOriginalExtension(),'public');
            $restaurante->img3 = $img3Name;            
        }
        $img4 = $request->file('img4');
        if ($img4 != null) {
            Storage::disk('public')->delete($restaurante->img4);
            $img4Name = $img4->storeAs('imgRestaurante','img4.'.$img4->getClientOriginalExtension(),'public');
            $restaurante->img4 = $img4Name;                        
        }

        $restaurante->save();

        return response()->json(
            [
                'data' => [
                    'nombre' =>  $restaurante->nombre,
                    'estado' =>  $restaurante->estado,
                    'iva' =>  $restaurante->iva,
                    'id' =>   $restaurante->id,
                ],                
                'imagenes' => [
                    'logo'=> asset('storage/'.$restaurante->logo),
                    'img1'=> asset('storage/'.$restaurante->img1),
                    'img2'=> asset('storage/'.$restaurante->img2),
                    'img3'=> asset('storage/'.$restaurante->img3),
                    'img4'=> asset('storage/'.$restaurante->img4),
                ],
                'HttpResponse' => [
                    'tittle' => 'Correcto',
                    'message' => 'Datos del restaurante actualizados',
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
