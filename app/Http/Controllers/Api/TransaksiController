<?php

namespace App\Http\Controllers\Api;

use App\Models\Transaksi;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data = Transaksi::all();
        return response()->json([
            'status' => 'success',
            'message' => 'Data Transaksi successfully',
            'data' => $data,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'number_akun_penerima' => 'required|numeric|exists:users,number_akun',
            'amount' => 'required|numeric|min:1',
        ]);

        // Gunakan database transaction untuk memastikan atomicity
        try {
            DB::beginTransaction();

            // Cari user pengirim
            $pengirim = User::findOrFail($validated['user_id']);

            // Cari user penerima berdasarkan number_akun
            $penerima = User::where('number_akun', $validated['number_akun_penerima'])->first();

            if (!$penerima) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Akun penerima tidak ditemukan',
                ], 404);
            }

            // Cek apakah pengirim dan penerima sama
            if ($pengirim->id === $penerima->id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak dapat melakukan transaksi ke akun sendiri',
                ], 400);
            }

            // Validasi balance pengirim
            if ($pengirim->balance < $validated['amount']) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Saldo tidak mencukupi. Saldo Anda: Rp ' . number_format($pengirim->balance, 2, ',', '.'),
                ], 400);
            }

            // Kurangi balance pengirim
            $pengirim->balance -= $validated['amount'];
            $pengirim->save();

            // Tambah balance penerima
            $penerima->balance += $validated['amount'];
            $penerima->save();

            // Simpan transaksi
            $transaksi = Transaksi::create([
                'user_id' => $pengirim->id,
                'pengirim' => $pengirim->name,
                'penerima' => $penerima->name,
                'amount' => $validated['amount'],
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Transaksi berhasil',
                'data' => [
                    'transaksi' => $transaksi,
                    'saldo_pengirim' => $pengirim->balance,
                    'saldo_penerima' => $penerima->balance,
                ],
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Transaksi gagal: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $data = Transaksi::find($id);
        if ($data) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data Transaksi found',
                'data' => $data,
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Data Transaksi not found',
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
        $transaksi = Transaksi::find($id);
        
        if (!$transaksi) {
            return response()->json([
                'status' => 'error',
                'message' => 'Transaksi not found',
            ], 404);
        }

        $transaksi->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Transaksi deleted successfully',
        ], 200);
    }
}


