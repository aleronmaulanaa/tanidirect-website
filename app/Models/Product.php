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
}

