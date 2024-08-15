<?php

namespace App\Http\Controllers;

use App\Models\Denda;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DendaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $totalDenda = Denda::sum('total_denda');
        $dendaLunas = Denda::where('status', 'lunas')->sum('total_denda');
        $dendaBelumLunas = Denda::where('status', 'belum_lunas')->sum('total_denda');

        return view('dashboard.denda', [
            'total_denda' => $totalDenda,
            'denda_lunas' => $dendaLunas,
            'denda_belum_lunas' => $dendaBelumLunas,
            'peminjaman' => Peminjaman::all(),
            'denda' => Denda::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Denda $denda)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Denda $denda)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id_denda)
    {
        DB::beginTransaction();

        try {
            $denda = Denda::findOrFail($id_denda);

            $denda->status = 'lunas';
            $denda->save();

            DB::commit();

            return redirect()->back()->with('toast', [
                'message' => 'Denda berhasil dibayar.',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors(['message' => 'Gagal membayar denda: ' . $e->getMessage()])->with('toast', [
                'message' => 'Gagal membayar denda: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Denda $denda)
    {
        //
    }
}
