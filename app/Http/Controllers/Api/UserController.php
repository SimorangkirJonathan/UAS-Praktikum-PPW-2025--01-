<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data = User::select('name', 'email', 'balance', 'number_akun')->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Data User successfully',
            'data' => $data,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'balance' => 'nullable|numeric|min:0',
            'number_akun' => 'required|numeric|unique:users,number_akun',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'balance' => $validated['balance'] ?? 0,
            'number_akun' => $validated['number_akun'],
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'data' => $user->only(['id', 'name', 'email', 'balance', 'number_akun']),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $data = User::select('name', 'email', 'balance', 'number_akun')->find($id);
        if ($data) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data User found',
                'data' => $data,
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Data User not found',
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found',
            ], 404);
        }

        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'User deleted successfully',
        ], 200);
    }
}
