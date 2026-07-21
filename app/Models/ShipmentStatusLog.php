<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShipmentStatusLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'status',
        'catatan',
        'diperbarui_pada',
    ];

    protected $casts = [
        'diperbarui_pada' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}