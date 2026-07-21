<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'buyer_id',
        'product_id',
        'order_pool_id',
        'jumlah',
        'total_harga',
        'status_pengiriman',
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'total_harga' => 'decimal:2',
    ];

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function orderPool(): BelongsTo
    {
        return $this->belongsTo(OrderPool::class, 'order_pool_id');
    }

    public function shipmentStatusLogs(): HasMany
    {
        return $this->hasMany(ShipmentStatusLog::class, 'order_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'order_id');
    }
}