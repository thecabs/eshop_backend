<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class GestionnaireController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomGest' => 'required|string|max:45',
            'typeGest' => 'required|numeric',
            'login' => 'required|string|unique:users,login',
            'password' => 'required|string',
            'actif' => 'boolean',
            'mobile' => 'required|string|max:20'
        ]);
        $validated['password'] = Hash::make($validated['password']);

        $gestionnaire = User::create($validated);
         
        return response ([
            'user' => $gestionnaire,
            'token' =>$gestionnaire->createToken('secret')->plainTextToken,
            'message' => 'User created successfully'

         ],201);
    
     //return response()->json(['message' => 'User created successfully'], 201);
    }

    public function login(Request $request)
    {
         
      
       $credentials = $request->only('login', 'password');
        if (Auth::attempt($credentials)) {
            $user = auth()->user();
            $token = $user->createToken('AuthToken')->plainTextToken;

            return response()->json(['user' => $user, 'token' => $token], 200);
        } else {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    }
    public function logout()
    {

        auth()->user()->tokens->each(function ($token, $key) {
            $token->delete();
        });
        return response(["message" => "deconnexion"], 200);
    }
    public function user()
    {
      
     return response ([
         'user'=> auth() -> user ()
     ], 200);
    }
    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        //
    }
}
