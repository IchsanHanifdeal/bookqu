<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function register()
    {
        return view('auth.register');
    }

    public function auth(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|string',
            'password' => 'required|string',
        ], [
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email salah.',
            'password.required' => 'Password harus diisi.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            $userRole = $user->role;

            $loginTime = Carbon::now();
            $request->session()->put([
                'login_time' => $loginTime->toDateTimeString(),
                'nama' => $user->nama,
                'id_user' => $user->id_user,
                'email' => $user->email,
                'role' => $user->role,
                'created_at' => $user->created_at,
            ]);

            if ($userRole === 'admin' || $userRole === 'petugas') {
                return redirect()->intended('dashboard')->with('toast', [
                    'message' => 'Login berhasil!',
                    'type' => 'success'
                ]);
            }

            return back()->with('toast', [
                'message' => 'Login gagal, role pengguna tidak dikenali.',
                'type' => 'error'
            ])->withInput();
        }

        return back()->withErrors([
            'loginError' => 'Email atau password salah.',
        ])->with('toast', [
            'message' => 'Email atau password salah.',
            'type' => 'error'
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('toast', [
            'message' => 'Logout berhasil!',
            'type' => 'success'
        ]);;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'required|string|max:255|unique:users,nik',
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string|max:255',
            'no_hp' => ['required', 'regex:/^62[0-9]{9,15}$/'],
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ], [
            'nik.required' => 'Nik/Sap harus diisi.',
            'nik.string' => 'Nik/Sap harus berupa teks.',
            'nik.max' => 'Nik/Sap maksimal 255 karakter.',
            'nik.unique' => 'Nik/Sap sudah digunakan, silakan gunakan yang lain.',
            'nama.required' => 'Nama harus diisi.',
            'nama.string' => 'Nama harus berupa teks.',
            'nama.max' => 'Nama maksimal 255 karakter.',
            'tempat_lahir.required' => 'Tempat lahir harus diisi.',
            'tempat_lahir.string' => 'Tempat lahir harus berupa teks.',
            'tempat_lahir.max' => 'Tempat lahir maksimal 255 karakter.',
            'tanggal_lahir.required' => 'Tanggal lahir harus diisi.',
            'tanggal_lahir.date' => 'Tanggal lahir harus berupa tanggal yang valid.',
            'alamat.required' => 'Alamat harus diisi.',
            'alamat.string' => 'Alamat harus berupa teks.',
            'alamat.max' => 'Alamat maksimal 255 karakter.',
            'no_hp.required' => 'Nomor handphone harus diisi.',
            'no_hp.regex' => 'Nomor handphone harus diawali dengan 62 dan hanya berupa angka.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Email harus berupa alamat email yang valid.',
            'email.unique' => 'Email sudah digunakan, silakan gunakan email lain.',
            'password.required' => 'Password harus diisi.',
            'password.string' => 'Password harus berupa teks.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)
                ->withInput()
                ->with('toast', [
                    'message' => 'Validasi gagal. Mohon perbaiki kesalahan dan coba lagi.',
                    'type' => 'error'
                ]);
        }

        try {
            $peserta = new User();
            $peserta->nik = $request->nik;
            $peserta->nama = $request->nama;
            $peserta->tempat = $request->tempat_lahir;
            $peserta->tanggal_lahir = $request->tanggal_lahir;
            $peserta->alamat = $request->alamat;
            $peserta->no_hp = $request->no_hp;
            $peserta->email = $request->email;
            $peserta->password = Hash::make($request->password);
            $peserta->save();

            return redirect()->route('login')->with('toast', [
                'message' => 'Peserta berhasil ditambahkan.',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            return back()->withErrors([
                'storeError' => 'Terjadi kesalahan saat menyimpan peserta. Mohon coba lagi.'
            ])->withInput()->with('toast', [
                'message' => 'Terjadi kesalahan saat menyimpan peserta. Mohon coba lagi.',
                'type' => 'error'
            ]);
        }
    }
}
