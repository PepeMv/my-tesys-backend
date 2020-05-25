<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;
use JWTAuth;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{   
    public function actualizarUsuario(Request $request, $id){
        $usuario = User::find($id);
        if (!$usuario) {
            return response()->json([
                'HttpResponse' => [
                    'tittle' => 'Error',
                    'message' => 'No se encontro el usuario!',
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
                'apellido' => 'required',
                'telefono' => 'required',
                'tipoUsuario' => 'required',
                'direccion' => 'required',
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
         $usuario->nombre = $request->nombre;
         $usuario->apellido = $request->apellido;
         $usuario->telefono = $request->telefono;
         $usuario->tipoUsuario = $request->tipoUsuario;
         $usuario->direccion = $request->direccion;

        if($request->password!=null){
            $usuario->password = Hash::make($request->password);
        }
        $usuario->save();

        return response()->json(
            [
                'usuario' => $usuario,
                'HttpResponse' => [
                    'tittle' => 'Correcto',
                    'message' => 'Usuario actualizado!',
                    'status' => 200,
                    'statusText' => 'success',
                    'ok' => true
                ],
            ],
            201
        );
    }

    public function register(Request $request)
    {
        
        $validation = Validator::make(
            $request->all(),
            [   
                'nombre' => 'required',
                'apellido' => 'required',
                'tipoDocumento' => 'required',
                'numeroDocumento' => 'required',
                'email' => 'required|email',
                'password' => 'required',
                'telefono' => 'required',
                'direccion' => 'required',
                'tipoUsuario' => 'required',
            ]
        );
        //print_r($validation->fails());
        if ($validation->fails()) {
            return response()->json(
                [
                    'HttpResponse' => [
                        'tittle' => 'Error',
                        'message' => 'El usuario con ese mail ya existe!',
                        'status' => 400,
                        'statusText' => 'error',
                        'ok' => true
                    ]
                ]
            );
        }
//echo($request->email);
        $usuarioPrevio = User::where('numeroDocumento', $request->numeroDocumento)->get();
        //echo($usuarioPrevio);
        //echo ($usuarioPrevio);
        if(!$usuarioPrevio->isEmpty()){
            return response()->json(
                [
                    'HttpResponse' => [
                        'tittle' => 'Error',
                        'message' => 'El usuario con ese numero de identificacion ya existe!',
                        'status' => 400,
                        'statusText' => 'error',
                        'ok' => true
                    ]
                ]
            );
        }

        $usuarioPrevio = User::where('email', $request->email)->get();
        //echo($usuarioPrevio);
        //echo ($usuarioPrevio);
        if(!$usuarioPrevio->isEmpty()){
            return response()->json(
                [
                    //'usuarioPrevio' => $usuarioPrevio,
                    'HttpResponse' => [
                        'tittle' => 'Error',
                        'message' => 'El usuario con ese mail ya existe!',
                        'status' => 400,
                        'statusText' => 'error',
                        'ok' => true
                    ]
                ]
            );
        }
       

        $user = new User();
        $user->nombre = $request->nombre;
        $user->apellido = $request->apellido;
        $user->tipoDocumento = $request->tipoDocumento;
        $user->numeroDocumento = $request->numeroDocumento;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->telefono = $request->telefono;
        $user->direccion = $request->direccion;
        $user->tipoUsuario = $request->tipoUsuario;

        $user->save();

        $token = JWTAuth::fromUser($user);

        return response()->json(
            [
                'usuario' => $user,
                'token' => $token,
                'HttpResponse' => [
                    'tittle' => 'Correcto',
                    'message' => 'Usuario creado!',
                    'status' => 200,
                    'statusText' => 'success',
                    'ok' => true
                ],
            ],
            201
        );
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                //return response()->json(['error' => 'invalid_credentials'], 400);
                return response()->json(
                    [                        
                        'HttpResponse' => [
                            'tittle' => 'Error',
                            'message' => 'Credenciales invalidas!',
                            'status' => 200,
                            'statusText' => 'error',
                            'ok' => true
                        ],
                    ],
                    400
                );
            }
        } catch (JWTException $e) {
            //return response()->json(['error' => 'could_not_create_token'], 500);
            return response()->json(
                [                        
                    'HttpResponse' => [
                        'tittle' => 'Error',
                        'message' => 'No se pudo crear el acceso!',
                        'status' => 200,
                        'statusText' => 'error',
                        'ok' => true
                    ],
                ],
                500
            );
        }
        //return response()->json(compact('token'));
        //return $this->respondWithToken($token);
        //$user = User::where('email','=',$request->email)->where('password','=',$request->password)->get();

        return response()->json(
            [
                //'usuario' => JWTAuth::user(),
                'token' => $token,
                'HttpResponse' => [
                    'tittle' => 'Correcto',
                    'message' => 'Logeado!',
                    'status' => 200,
                    'statusText' => 'success',
                    'ok' => true
                ],
            ],
            201
        );
    }

    public function logout()
    {
        JWTAuth::invalidate();

        //return response()->json(['message' => 'Successfully logged out']);
        return response()->json(
            [
                'HttpResponse' => [
                    'tittle' => 'Correcto',
                    'message' => 'Ya no esta logeado!',
                    'status' => 200,
                    'statusText' => 'success',
                    'ok' => true
                ],
            ],
            201
        );
    }
    
    //-------------------------------
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(
            [
                'usuarios' => User::all(),
                'HttpResponse' => [
                    'status' => 200,
                    'statusText' => 'OK',
                    'ok' => true
                ]
            ],
            201
        );
    }

    public function getUserByid(Request $request){
        //echo($request->id);
        $usuario = User::find($request->id);
        if (!$usuario) {
            return response()->json([
                'HttpResponse' => [
                    'tittle' => 'Error',
                    'message' => 'No se encontro el usuario!',
                    'status' => 400,
                    'statusText' => 'error',
                    'ok' => true
                ]
            ]);
        }else{
            return response()->json(
                [
                    'usuario' => $usuario,
                    'HttpResponse' => [
                        'status' => 200,
                        'statusText' => 'success',
                        'ok' => true
                    ]
                ],
                201
            );
        }
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
        //
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
