<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt('password')
        ]);

        Wallet::create([
            'user_id' => $user->id,
            'balance' => 100000
        ]);

        return response()->json($user, 201);
    }
}
