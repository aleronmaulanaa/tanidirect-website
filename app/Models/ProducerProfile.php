<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProducerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lokasi_desa',
        'kabupaten_kota',
        'komoditas_utama',
        'status_verifikasi',
        'dokumen_verifikasi',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'producer_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'producer_id');
    }
}

