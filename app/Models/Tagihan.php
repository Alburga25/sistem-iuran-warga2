<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Tagihan extends Model
{
    protected $fillable = [
        'user_id',
        'iuran_id',
        'tanggal_jatuh_tempo',
        'status',
        'order_id',
        'snap_token',
        'settlement_time',
        'jumlah_bayar',
    ];

    protected $casts = [
        'settlement_time' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function iuran(): BelongsTo
    {
        return $this->belongsTo(Iuran::class);
    }
}
