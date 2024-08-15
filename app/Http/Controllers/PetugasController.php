<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PetugasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $petugas_terbaru = User::where('role', 'petugas')->latest()->first();
        $petugas_terbaru_name = $petugas_terbaru ? $petugas_terbaru->nama : 'Tidak Ada Petugas Terbaru';

        $jumlah_petugas = User::where('role', 'petugas')->count();

        return view('dashboard.petugas', [
            'petugas' => User::where('role', 'petugas')->get(),
            'petugas_terbaru' => $petugas_terbaru_name,
            'jumlah_petugas' => $jumlah_petugas,
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
        $rules = [
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'no_hp' => ['required', 'numeric', 'starts_with:62'],
            'password' => 'required|string|min:8',
        ];

        $messages = [
            'no_hp.starts_with' => 'Nomor HP harus diawali dengan 62.',
        ];

        $request->validate($rules, $messages);

        DB::beginTransaction();
        try {
            $user = new User();
            $user->nama = $request->input('nama');
            $user->email = $request->input('email');
            $user->no_hp = $request->input('no_hp');
            $user->role = 'petugas';
            $user->password = Hash::make($request->input('password')); // Hash the password

            $user->save();
            DB::commit();

            return redirect()->back()->with('toast', [
                'message' => 'Petugas berhasil ditambahkan.',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => $e->getMessage()])
                ->with('toast', [
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                    'type' => 'error'
                ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id_user)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id_user . ',id_user',
            'no_hp' => ['required', 'numeric', 'starts_with:62'],
            'password' => 'nullable|string|min:8',
        ], [
            'no_hp.starts_with' => 'Nomor HP harus diawali dengan 62.',
        ]);

        DB::beginTransaction();
        try {
            $user = User::findOrFail($id_user);
            $user->nama = $request->input('nama');
            $user->email = $request->input('email');
            $user->no_hp = $request->input('no_hp');

            if ($request->filled('password')) {
                $user->password = Hash::make($request->input('password'));
            }

            $user->save();
            DB::commit();

            return redirect()->back()->with('toast', [
                'message' => 'Petugas berhasil diperbarui.',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => $e->getMessage()])
                ->with('toast', [
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                    'type' => 'error'
                ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_user)
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id_user);
            $user->delete();
            DB::commit();

            return redirect()->back()->with('toast', [
                'message' => 'Petugas berhasil dihapus.',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => $e->getMessage()])
                ->with('toast', [
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                    'type' => 'error'
                ]);
        }
    }
}
