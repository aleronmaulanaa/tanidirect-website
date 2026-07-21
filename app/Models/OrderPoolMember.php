<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderPoolMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_pool_id',
        'buyer_id',
        'jumlah',
        'bergabung_pada',
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'bergabung_pada' => 'datetime',
    ];

    public function orderPool(): BelongsTo
    {
        return $this->belongsTo(OrderPool::class);
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }
}