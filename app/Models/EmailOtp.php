<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailOtp extends Model
{
    protected $fillable = ['email', 'otp', 'expires_at', 'is_verified'];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_verified' => 'boolean',
    ];

    public function isExpired(): bool
    {
        return now()->greaterThan($this->expires_at);
    }
}
