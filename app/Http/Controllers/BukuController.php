<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BukuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $buku_terbaru = Buku::latest()->first();
        $buku_terbaru_title = $buku_terbaru ? $buku_terbaru->judul : 'Tidak Ada Buku Terbaru';

        $jumlah_buku = Buku::sum('stock');

        return view('dashboard.buku', [
            'buku' => Buku::all(),
            'buku_terbaru' => $buku_terbaru_title,
            'jumlah_buku' => $jumlah_buku,
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
        $request->validate([
            'cover' => 'required',
            'judul' => 'required|string',
            'penerbit' => 'required|string|max:255',
            'penulis' => 'required|string',
            'tahun' => 'required|digits:4',
            'stock' => 'required|integer',
        ]);

        DB::beginTransaction();
        try {
            $fileName = null;

            if ($request->hasFile('cover')) {
                $cover = $request->file('cover');
                $extension = $cover->extension();

                $formattedDate = now()->format('md');
                $judulFormatted = str_replace(' ', '_', $request->judul); // Replace spaces with underscores for filename
                $fileName = 'COV' . $formattedDate . '' . $judulFormatted . '.' . $extension;

                $cover->storeAs('public/buku', $fileName);
            }

            $buku = new Buku();
            $buku->judul = $request->judul;
            $buku->penerbit = $request->penerbit;
            $buku->penulis = $request->penulis;
            $buku->tahun = $request->tahun;
            $buku->stock = $request->stock;
            $buku->cover = $fileName;

            $buku->save();
            DB::commit();

            return redirect()->back()->with('toast', [
                'message' => 'Buku berhasil ditambahkan.',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['file_materi' => $e->getMessage()])
                ->withInput()
                ->with('toast', [
                    'message' => $e->getMessage(),
                    'type' => 'error'
                ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Buku $buku)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Buku $buku)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id_buku)
    {
        // Validate the request
        $request->validate([
            'cover' => 'nullable|image',
            'judul' => 'required|string',
            'penerbit' => 'required|string|max:255',
            'penulis' => 'required|string',
            'tahun' => 'required|digits:4',
            'stock' => 'required|integer',
        ]);

        DB::beginTransaction();
        try {
            $buku = Buku::findOrFail($id_buku);
            $fileName = $buku->cover;

            if ($request->hasFile('cover')) {
                $cover = $request->file('cover');
                $extension = $cover->extension();

                $formattedDate = now()->format('md'); // Format month-day
                $judulFormatted = str_replace(' ', '_', $request->judul); // Replace spaces with underscores for filename
                $fileName = 'COV' . $formattedDate . '_' . $judulFormatted . '.' . $extension;

                $cover->storeAs('public/buku', $fileName);

                if ($buku->cover && Storage::exists('public/buku/' . $buku->cover)) {
                    Storage::delete('public/buku/' . $buku->cover);
                }
            }

            $buku->judul = $request->judul;
            $buku->penerbit = $request->penerbit;
            $buku->penulis = $request->penulis;
            $buku->tahun = $request->tahun;
            $buku->stock = $request->stock;
            $buku->cover = $fileName;

            $buku->save();
            DB::commit();

            return redirect()->back()->with('toast', [
                'message' => 'Buku berhasil diperbarui.',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['file_materi' => $e->getMessage()])
                ->withInput()
                ->with('toast', [
                    'message' => $e->getMessage(),
                    'type' => 'error'
                ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_buku)
    {
        DB::beginTransaction();
        try {
            $buku = Buku::findOrFail($id_buku);

            if ($buku->cover && Storage::exists('public/buku/' . $buku->cover)) {
                Storage::delete('public/buku/' . $buku->cover);
            }

            $buku->delete();
            DB::commit();

            return redirect()->back()->with('toast', [
                'message' => 'Buku berhasil dihapus.',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['file_materi' => $e->getMessage()])
                ->with('toast', [
                    'message' => $e->getMessage(),
                    'type' => 'error'
                ]);
        }
    }
}
