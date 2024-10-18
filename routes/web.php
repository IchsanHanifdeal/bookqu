<?php

use Carbon\Carbon;
use App\Models\Buku;
use App\Models\User;
use App\Models\Denda;
use App\Models\Anggota;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\DendaController;
use App\Http\Controllers\JenisController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/auth', [AuthController::class, 'auth'])->name('auth');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $today = Carbon::today();

        $jumlah_peminjaman = Peminjaman::count();
        $jumlah_buku = Buku::count();
        $jumlah_petugas = Auth::User()->role === 'admin' ? User::where('role', 'petugas')->count() : 0;
        $jumlah_anggota = Anggota::count();

        $jumlah_denda = Denda::sum('total_denda');
        $denda_belum_lunas = Denda::where('status', 'belum bayar')->sum('total_denda');
        $denda_lunas = Denda::where('status', 'lunas')->sum('total_denda');

        $buku_baru = Buku::orderBy('created_at', 'desc')->limit(5)->get();
        $petugas_baru = Auth::user()->role === 'admin' ? User::where('role', 'petugas')->orderBy('created_at', 'desc')->limit(5)->get() : collect([]);
        $anggota_baru = Anggota::orderBy('created_at', 'desc')->limit(5)->get();

        $buku = Buku::whereDate('created_at', $today)->get();
        $petugas = Auth::user()->role === 'admin' ? User::whereDate('created_at', $today)->get() : collect([]);
        $anggota = Anggota::whereDate('created_at', $today)->get();

        return view('dashboard.index', [
            'today' => $today,
            'jumlah_peminjaman' => $jumlah_peminjaman,
            'jumlah_buku' => $jumlah_buku,
            'jumlah_petugas' => $jumlah_petugas,
            'jumlah_anggota' => $jumlah_anggota,
            'jumlah_denda' => $jumlah_denda,
            'denda_belum_lunas' => $denda_belum_lunas,
            'denda_lunas' => $denda_lunas,
            'buku_baru' => $buku_baru,
            'petugas_baru' => $petugas_baru,
            'anggota_baru' => $anggota_baru,
            'buku' => $buku,
            'petugas' => $petugas,
            'anggota' => $anggota,
        ]);
    })->name('dashboard');
    Route::post('/dashboard', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard/buku', [BukuController::class, 'index'])->name('buku');
    Route::post('/dashboard/buku/store', [BukuController::class, 'store'])->name('store.buku');
    Route::put('/dashboard/buku/{id_buku}/update', [BukuController::class, 'update'])->name('update.buku');
    Route::delete('/dashboard/buku/{id_buku}/delete', [BukuController::class, 'destroy'])->name('delete.buku');

    Route::get('/dashboard/petugas', [PetugasController::class, 'index'])->name('petugas');
    Route::post('/dashboard/petugas/store', [PetugasController::class, 'store'])->name('store.petugas');
    Route::put('/dashboard/petugas/{id_user}/update', [PetugasController::class, 'update'])->name('update.petugas');
    Route::delete('/dashboard/petugas/{id_user}/delete', [PetugasController::class, 'destroy'])->name('destroy.petugas');

    Route::get('/dashboard/anggota', [AnggotaController::class, 'index'])->name('anggota');
    Route::post('/dashboard/anggota/store', [AnggotaController::class, 'store'])->name('store.anggota');
    Route::put('/dashboard/anggota/{id_anggota}/update', [AnggotaController::class, 'update'])->name('update.anggota');
    Route::delete('/dashboard/anggota/{id_update}/delete', [AnggotaController::class, 'destroy'])->name('destroy.anggota');

    Route::get('/dashboard/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman');
    Route::post('/dashboard/peminjaman/store', [PeminjamanController::class, 'store'])->name('store.peminjaman');
    Route::put('/dashboard/peminjaman/{id_peminjaman}/update', [PeminjamanController::class, 'update'])->name('update.peminjaman');
    Route::delete('/dashboard/peminjaman/delete', [PeminjamanController::class, 'destroy'])->name('destroy.peminjaman');

    Route::get('/dashboard/denda', [DendaController::class, 'index'])->name('denda');
    Route::post('/dashboard/denda/store', [DendaController::class, 'store'])->name('store.denda');
    Route::put('/dashboard/denda/{id_denda}/update', [DendaController::class, 'update'])->name('update.denda');
    Route::delete('/dashboard/denda/delete', [DendaController::class, 'destroy'])->name('destroy.denda');

    Route::get('/dashboard/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/dashboard/profile/{id_user}/update', [ProfileController::class, 'update'])->name('update.profile');
    Route::put('/dashboard/profile/{id_user}/pwd', [ProfileController::class, 'password'])->name('change_password.profile');

    Route::post('/dashboard/logout', [AuthController::class, 'logout'])->name('logout');
});
