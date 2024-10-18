<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnggotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    private function generateUniqueNoAnggota()
    {
        $currentDate = Carbon::now()->format('md');
        $prefix = 'ANG' . $currentDate;

        $lastAnggota = Anggota::where('no_anggota', 'LIKE', "{$prefix}%")->orderBy('no_anggota', 'desc')->first();

        if ($lastAnggota) {
            $lastNumber = intval(substr($lastAnggota->no_anggota, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return $prefix . $newNumber;
    }

    public function index()
    {
        $noAnggota = $this->generateUniqueNoAnggota();
        $anggota_terbaru = Anggota::latest()->first();

        return view('dashboard.anggota', [
            'anggota_terbaru' => $anggota_terbaru,
            'anggota' => Anggota::all(),
            'no_anggota' => $noAnggota,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $validatedData = $request->validate([
                'nik' => 'required|unique:anggota,nik',
                'nama' => 'required',
                'tempat' => 'required',
                'tanggal_lahir' => 'required|date',
                'no_anggota' => 'required|unique:anggota,no_anggota',
                'alamat' => 'required',
                'no_hp' => ['required', 'unique:anggota,no_hp', 'regex:/^62[0-9]{8,}$/'],
                'email' => 'required|unique:anggota,email',
            ], [
                'nik.unique' => 'Nik sudah digunakan.',
                'nama.required' => 'Nama wajib diisi.',
                'tempat.required' => 'Tempat wajib diisi.',
                'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
                'tanggal_lahir.date' => 'Tanggal lahir harus berupa tanggal yang valid.',
                'no_anggota.required' => 'Nomor anggota wajib diisi.',
                'no_anggota.unique' => 'Nomor anggota sudah digunakan.',
                'alamat.required' => 'Alamat wajib diisi.',
                'no_hp.required' => 'Nomor HP wajib diisi.',
                'no_hp.unique' => 'Nomor HP sudah digunakan.',
                'no_hp.regex' => 'Nomor HP harus diawali dengan 62 dan memiliki minimal 10 digit setelah 62.',
                'email.required' => 'Email wajib diisi.',
                'email.unique' => 'Email sudah digunakan.',
            ]);

            $validatedData['tanggal_bergabung'] = Carbon::today()->toDateString();

            Anggota::create($validatedData);

            DB::commit();

            return redirect()->route('anggota.index')->with('toast', [
                'message' => 'Anggota berhasil ditambahkan.',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors(['message' => 'Gagal menyimpan nilai. Silakan coba lagi.'])->withInput();
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Anggota $anggota)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Anggota $anggota)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id_anggota)
    {
        DB::beginTransaction();

        try {
            $validatedData = $request->validate([
                'nama' => 'required',
                'tempat' => 'required',
                'tanggal_lahir' => 'required|date',
                'no_anggota' => 'required|unique:anggota,no_anggota,' . $id_anggota . ',id_anggota',
                'alamat' => 'required',
                'no_hp' => ['required', 'regex:/^62[0-9]{8,}$/', 'unique:anggota,no_hp,' . $id_anggota . ',id_anggota'],
                'email' => 'required|email|unique:anggota,email,' . $id_anggota . ',id_anggota',
            ], [
                'nama.required' => 'Nama wajib diisi.',
                'tempat.required' => 'Tempat wajib diisi.',
                'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
                'tanggal_lahir.date' => 'Tanggal lahir harus berupa tanggal yang valid.',
                'no_anggota.required' => 'Nomor anggota wajib diisi.',
                'no_anggota.unique' => 'Nomor anggota sudah digunakan.',
                'alamat.required' => 'Alamat wajib diisi.',
                'no_hp.required' => 'Nomor HP wajib diisi.',
                'no_hp.unique' => 'Nomor HP sudah digunakan.',
                'no_hp.regex' => 'Nomor HP harus diawali dengan 62 dan memiliki minimal 10 digit setelah 62.',
                'email.required' => 'Email wajib diisi.',
                'email.unique' => 'Email sudah digunakan.',
            ]);

            $validatedData['tanggal_bergabung'] = Carbon::today()->toDateString();

            $anggota = Anggota::findOrFail($id_anggota);
            $anggota->update($validatedData);

            DB::commit();

            return redirect()->route('anggota.index')->with('toast', [
                'message' => 'Anggota berhasil diperbarui.',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors(['message' => 'Gagal memperbarui data. Silakan coba lagi.'])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_anggota)
    {
        DB::beginTransaction();

        try {
            $anggota = Anggota::findOrFail($id_anggota);
            $anggota->delete();

            DB::commit();

            return redirect()->route('anggota.index')->with('toast', [
                'message' => 'Anggota berhasil dihapus.',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors(['message' => 'Gagal menghapus data. Silakan coba lagi.']);
        }
    }
}
