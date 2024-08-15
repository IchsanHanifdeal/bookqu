<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Buku;
use App\Models\User;
use App\Models\Denda;
use App\Models\Anggota;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tanggal = date('Y-m-d');
        $bukuIds = Peminjaman::whereDate('created_at', $tanggal)->pluck('id_buku');
        $buku_baru_dipinjam = Buku::whereIn('id_buku', $bukuIds)->first();

        return view('dashboard.peminjaman', [
            'buku_baru_dipinjam' => $buku_baru_dipinjam,
            'peminjaman' => Peminjaman::all(),
            'petugas' => User::all(),
            'anggota' => Anggota::all(),
            'buku' => Buku::all(),
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
        DB::beginTransaction();

        try {
            $validatedData = $request->validate([
                'petugas' => 'required|exists:users,id_user',
                'anggota' => [
                    'required',
                    'exists:anggota,id_anggota',
                    function ($attribute, $value, $fail) {
                        $anggota = Anggota::find($value);

                        $unpaidFines = Denda::where('id_anggota', $anggota->id_anggota)
                            ->where('status', 'belum bayar')
                            ->count();

                        if ($unpaidFines > 0) {
                            $fail('Anggota masih memiliki denda yang belum dibayar.');
                        }

                        $unreturnedBooks = Peminjaman::where('id_anggota', $anggota->id_anggota)
                            ->where('status', '!=', 'dikembalikan')
                            ->count();

                        if ($unreturnedBooks > 0) {
                            $fail('Anggota masih memiliki buku yang belum dikembalikan.');
                        }
                    }
                ],
                'buku' => 'required|exists:buku,id_buku',
                'tanggal_peminjaman' => 'required|date',
                'tanggal_pengembalian' => 'required|date|after_or_equal:tanggal_peminjaman',
                'jumlah' => [
                    'required',
                    'integer',
                    function ($attribute, $value, $fail) use ($request) {
                        $buku = Buku::find($request->buku);
                        if ($buku && $value > $buku->stock) {
                            $fail('Jumlah peminjaman tidak boleh melebihi stok buku.');
                        }
                    }
                ],
            ], [
                'petugas.required' => 'Petugas wajib dipilih.',
                'anggota.required' => 'Anggota wajib dipilih.',
                'buku.required' => 'Buku wajib dipilih.',
                'tanggal_peminjaman.required' => 'Tanggal peminjaman wajib diisi.',
                'tanggal_pengembalian.required' => 'Tanggal pengembalian wajib diisi.',
                'tanggal_pengembalian.after_or_equal' => 'Tanggal pengembalian tidak boleh sebelum tanggal peminjaman.',
                'jumlah.required' => 'Jumlah wajib diisi.',
                'jumlah.integer' => 'Jumlah harus berupa angka.',
            ]);

            Peminjaman::create([
                'id_user' => $request->petugas,
                'id_anggota' => $request->anggota,
                'id_buku' => $request->buku,
                'tanggal_peminjaman' => $request->tanggal_peminjaman,
                'tanggal_pengembalian' => $request->tanggal_pengembalian,
                'jumlah' => $request->jumlah,
            ]);

            $buku = Buku::find($request->buku);
            $buku->stock -= $request->jumlah;
            $buku->save();

            DB::commit();

            return redirect()->back()->with('toast', [
                'message' => 'Peminjaman berhasil ditambahkan.',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors(['message' => 'Gagal menyimpan peminjaman: ' . $e->getMessage()])->withInput()->with('toast', [
                'message' => 'Gagal menyimpan peminjaman: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function update(Request $request, $id_peminjaman)
    {
        DB::beginTransaction();

        try {
            $peminjaman = Peminjaman::findOrFail($id_peminjaman);
            $buku = Buku::findOrFail($peminjaman->id_buku);

            $buku->stock += $peminjaman->jumlah;
            $buku->save();

            $peminjaman->tanggal_dikembalikan = now();
            $peminjaman->status = 'dikembalikan';
            $peminjaman->save();

            $tanggal_pengembalian = Carbon::parse($peminjaman->tanggal_pengembalian);
            $tanggal_dikembalikan = Carbon::parse($peminjaman->tanggal_dikembalikan);

            if ($tanggal_dikembalikan->gt($tanggal_pengembalian)) {
                $daysLate = $tanggal_dikembalikan->diffInDays($tanggal_pengembalian);
                $fineAmount = $daysLate * 3000;

                Denda::create([
                    'id_anggota' => $peminjaman->id_anggota,
                    'id_peminjaman' => $peminjaman->id_peminjaman,
                    'total_denda' => $fineAmount,
                    'status' => 'belum bayar'
                ]);
            }

            DB::commit();

            return redirect()->back()->with('toast', [
                'message' => 'Buku berhasil dikembalikan.',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors(['message' => 'Gagal mengembalikan buku: ' . $e->getMessage()])->with('toast', [
                'message' => 'Gagal mengembalikan buku: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Peminjaman $peminjaman)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Peminjaman $peminjaman)
    {
        //
    }
}
