<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  

class TransactionController extends Controller
{
    public function transfer(Request $request)
    {
        $request->validate([
            'sender_id' => 'required',
            'receiver_id' => 'required',
            'amount' => 'required|numeric|min:1'
        ]);

        DB::beginTransaction();

        try {
            $sender = Wallet::where('user_id', $request->sender_id)->lockForUpdate()->first();
            $receiver = Wallet::where('user_id', $request->receiver_id)->lockForUpdate()->first();

            if (!$sender || !$receiver) {
                throw new \Exception('Wallet not found');
            }

            if ($sender->balance < $request->amount) {
                throw new \Exception('Insufficient balance');
            }

            $sender->balance -= $request->amount;
            $sender->save();

            // ❗ SIMULASI ERROR (UNTUK TEST ROLLBACK)
            if ($request->amount > 50000) {
                throw new \Exception('Simulated failure');
            }

            $receiver->balance += $request->amount;
            $receiver->save();

            Transaction::create([
                'sender_id' => $request->sender_id,
                'receiver_id' => $request->receiver_id,
                'amount' => $request->amount,
                'status' => 'SUCCESS'
            ]);

            DB::commit();

            return response()->json(['message' => 'Transfer success']);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }
}