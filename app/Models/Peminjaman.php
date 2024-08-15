<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Peminjaman extends Model
{
    use HasFactory;
    protected $table = 'peminjaman';
    protected $primaryKey = 'id_peminjaman';
    protected $fillable = [
        'id_buku',
        'id_user',
        'id_anggota',
        'tanggal_peminjaman',
        'tanggal_pengembalian',
        'status',
        'jumlah'
    ];

    protected $dates = ['tanggal_peminjaman', 'tanggal_pengembalian', 'tanggal_dikembalikan'];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'id_anggota', 'id_anggota');
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'id_buku', 'id_buku');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function getLatenessAttribute()
    {

        $dueDate = Carbon::parse($this->tanggal_pengembalian);
        $returnDate = Carbon::parse($this->tanggal_dikembalikan);

        $lateness = $returnDate->diffInDays($dueDate, false);

        return $lateness > 0 ? $lateness : 0;
    }
}
