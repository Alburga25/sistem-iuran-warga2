<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotifikasiLog extends Model
{
    protected $fillable = ['user_id', 'pesan', 'dikirim_pada'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

