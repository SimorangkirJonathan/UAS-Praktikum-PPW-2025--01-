<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function show($user_id)
    {
        $wallet = Wallet::where('user_id', $user_id)->first();

        return response()->json($wallet);
    }
}
