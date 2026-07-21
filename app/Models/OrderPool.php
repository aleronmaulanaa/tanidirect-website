<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderPool extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'target_volume',
        'volume_terkumpul',
        'status',
        'batas_waktu',
    ];

    protected $casts = [
        'target_volume' => 'integer',
        'volume_terkumpul' => 'integer',
        'batas_waktu' => 'datetime',
    ];

    public function product(): BelongsTo
    {
       return $this->belongsTo(Product::class, 'product_id');
    }

    public function members(): HasMany
    {
        return $this->hasMany(OrderPoolMember::class, 'order_pool_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'order_pool_id');
    }
}