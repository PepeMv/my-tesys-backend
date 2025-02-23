<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('auth:api')->get('/user', function (Request $request) {
    //Restaurante
    
});
    //datos de facturacio
    Route::get('datosFacturacion/{id}', 'DatosFacturacionController@show');
    Route::post('datosFacturacion', 'DatosFacturacionController@store');

    //login 
    Route::post('usuario/login', 'UserController@authenticate');
    Route::post('usuario/logout', 'UserController@logout');
    Route::post('usuario/registrar', 'UserController@register');
    // usuarios
    Route::get('usuarios', 'UserController@index');
    Route::put('usuarios/{id}', 'UserController@actualizarUsuario');
    Route::get('usuariosById', 'UserController@getUserByid');

    //Restaurante
    Route::get('restaurante', 'RestauranteController@index');
    Route::post('restaurante/update/{id}', 'RestauranteController@update');
    

    //categorias
    Route::get('categorias', 'CategoriaController@index');
    Route::post('categorias', 'CategoriaController@store');
    Route::put('categorias/{id}', 'CategoriaController@update');
    Route::delete('categorias/{id}', 'CategoriaController@destroy');

    //productos
    Route::get('productos', 'ProductoController@index');
    Route::post('productos/store', 'ProductoController@store');
    Route::post('productos/update/{id}', 'ProductoController@update');
    Route::delete('productos/{id}', 'ProductoController@destroy');

    //mesas
    Route::get('mesas', 'MesaController@index');
    Route::post('mesas', 'MesaController@store');
    Route::put('mesas/{id}', 'MesaController@update');
    Route::delete('mesas/{id}', 'MesaController@destroy');

    //pedidos
    Route::post('pedidos', 'PedidoController@store');
    //obtener pedidos para preparar
    Route::get('pedidosPreparar', 'PedidoController@getPedidosParaPreparar');
    //pedidos para entregar
    Route::get('pedidosEntregar', 'PedidoController@getPedidosParaEntregar');
    //obetner pedidos x usuario
    Route::get('pedidosByUsuario/{id}', 'PedidoController@getPedidosByUsuario');
    //todos los pedidos para buscar 
    Route::get('pedidosGeneral', 'PedidoController@getPedidosyDetallesGeneral');
    //actualizar estado pedido
    Route::put('actulizarPedido/{id}', 'PedidoController@actualizarEstadoPedido');