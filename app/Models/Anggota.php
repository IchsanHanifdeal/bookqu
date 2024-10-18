<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Anggota extends Model
{
    use HasFactory;
    protected $table = 'anggota';
    protected $primaryKey = 'id_anggota';
    protected $fillable = [
        'nik',
        'nama',
        'tempat',
        'tanggal_lahir',
        'no_anggota',
        'alamat',
        'no_hp',
        'email',
        'tanggal_bergabung'
    ];

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'id_anggota', 'id_anggota');
    }

    public function denda()
    {
        return $this->hasMany(Denda::class, 'id_anggota', 'id_anggota');
    }

    public function setTanggalLahirAttribute($value)
    {
        $this->attributes['tanggal_lahir'] = $value;
        $this->attributes['no_anggota'] = $this->generateUniqueNoAnggota($value);
    }

    private function generateUniqueNoAnggota($tanggal_lahir)
    {
        $tanggal = Carbon::parse($tanggal_lahir)->format('md');
        $prefix = 'ANG' . $tanggal;

        $lastAnggota = self::where('no_anggota', 'LIKE', "{$prefix}%")->orderBy('no_anggota', 'desc')->first();

        if ($lastAnggota) {
            $lastNumber = intval(substr($lastAnggota->no_anggota, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return $prefix . $newNumber;
    }
}
