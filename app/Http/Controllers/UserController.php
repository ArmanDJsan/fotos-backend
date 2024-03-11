<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Str;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json( UserResource::collection($users), 200);
    }

    public function show($usuario)
    {
        $user = User::where('url', $usuario)->first();
        if ($user && isset($user->images))
            return response()->json($user->images, 200);
        return response()->json([], 404);
    }
    public function generate()
    {
        $urlAleatoria =  $this->generarUrlCorta();
        $newUser =  User::create(
            [
                'email' => $urlAleatoria . '@storage.com',
                'name' => 'anonimo',
                'password' => Hash::make('password'),
                'url' => $urlAleatoria,
            ]
        );
        return response()->json($newUser, 201);
    }

    function generarUrlCorta()
    {
        // Generar una cadena aleatoria de 8 caracteres
        $codigoAleatorio = Str::random(8);

        // Verificar si el código ya existe en la base de datos
        $urlExistente = User::where('url', $codigoAleatorio)->first();

        // Si el código ya existe, generar uno nuevo
        while ($urlExistente) {
            $codigoAleatorio = Str::random(8);
            $urlExistente = User::where('url', $codigoAleatorio)->first();
        }
        return $codigoAleatorio;
    }
}
