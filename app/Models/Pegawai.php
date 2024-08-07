<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'posisi',
        'tanggal_masuk',
        'foto',
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
    ];
}
