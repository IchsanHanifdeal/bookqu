<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('anggota', function (Blueprint $table) {
            $table->id('id_anggota');
            $table->string('nik')->unique();
            $table->string('nama');
            $table->string('tempat');
            $table->date('tanggal_lahir');
            $table->string('no_anggota')->unique();
            $table->string('alamat');
            $table->string('no_hp')->unique();
            $table->string('email')->unique();
            $table->date('tanggal_bergabung');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggota');
    }
};
