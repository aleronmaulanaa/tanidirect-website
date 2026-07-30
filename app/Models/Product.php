<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'producer_id',
        'kategori',
        'nama_produk',
        'harga_jual',
        'stok',
        'satuan',
        'deskripsi',
        'gambar',
        'is_active',
    ];

    protected $casts = [
        'harga_jual' => 'decimal:2',
        'stok' => 'integer',
        'is_active' => 'boolean',
    ];

    public function producer(): BelongsTo
    {
        return $this->belongsTo(ProducerProfile::class, 'producer_id');
    }

    public function orderPools(): HasMany
    {
        return $this->hasMany(OrderPool::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'product_id');
    }

    /**
     * Relasi ulasan produk melalui tabel pesanan (orders).
     */
    public function reviews(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(Review::class, Order::class, 'product_id', 'order_id');
    }

    /**
     * Accessor untuk nilai rata-rata rating (float 1 desimal, misal 4.8).
     */
    public function getAverageRatingAttribute(): float
    {
        $avg = $this->reviews()->avg('rating');

        return $avg ? round((float) $avg, 1) : 0.0;
    }

    /**
     * Accessor untuk total jumlah ulasan produk.
     */
    public function getTotalReviewsAttribute(): int
    {
        return $this->reviews()->count();
    }
}

