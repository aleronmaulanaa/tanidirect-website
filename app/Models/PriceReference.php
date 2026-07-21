<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceReference extends Model
{
    use HasFactory;

    protected $fillable = [
        'kategori_komoditas',
        'kabupaten_kota',
        'sumber',
        'tipe_harga',
        'periode',
        'harga',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
    ];
}