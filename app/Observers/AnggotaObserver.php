<?php

namespace App\Observers;

use Carbon\Carbon;
use App\Models\Anggota;

class AnggotaObserver
{
    /**
     * Handle the Anggota "created" event.
     */
    public function created(Anggota $anggota): void
    {
        if (empty($anggota->no_anggota)) {
            $tanggal = Carbon::parse($anggota->tanggal_lahir)->format('md');
            $prefix = 'ANG' . $tanggal;

            $lastAnggota = Anggota::where('no_anggota', 'LIKE', "{$prefix}%")->orderBy('no_anggota', 'desc')->first();

            if ($lastAnggota) {
                $lastNumber = intval(substr($lastAnggota->no_anggota, -4));
                $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            } else {
                $newNumber = '0001';
            }

            $anggota->no_anggota = $prefix . $newNumber;
        }
    }

    /**
     * Handle the Anggota "updated" event.
     */
    public function updated(Anggota $anggota): void
    {
        //
    }

    /**
     * Handle the Anggota "deleted" event.
     */
    public function deleted(Anggota $anggota): void
    {
        //
    }

    /**
     * Handle the Anggota "restored" event.
     */
    public function restored(Anggota $anggota): void
    {
        //
    }

    /**
     * Handle the Anggota "force deleted" event.
     */
    public function forceDeleted(Anggota $anggota): void
    {
        //
    }
}
