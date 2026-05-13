<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'password',
        'role',
        'is_active',
        'avatar_url',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // JWT
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'role' => $this->role,
        ];
    }

    // Relationships
    public function lawyerProfile()
    {
        return $this->hasOne(LawyerProfile::class, 'user_id');
    }

    public function lawyerDocuments()
    {
        return $this->hasMany(LawyerDocument::class, 'lawyer_id');
    }

    public function clientCases()
    {
        return $this->hasMany(LegalCase::class, 'client_id');
    }

    public function lawyerCases()
    {
        return $this->hasMany(LegalCase::class, 'lawyer_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function deviceTokens()
    {
        return $this->hasMany(DeviceToken::class, 'user_id');
    }

    public function refreshTokens()
    {
        return $this->hasMany(RefreshToken::class, 'user_id');
    }
}
