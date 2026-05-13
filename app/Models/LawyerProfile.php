<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LawyerProfile extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'bar_number',
        'specializations',
        'years_of_experience',
        'bio',
        'is_available',
        'last_lat',
        'last_lng',
        'last_location_at',
        'sub_district',
        'city_district',
        'province',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'rating_avg',
        'total_cases',
        'total_earned',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'last_lat' => 'decimal:8',
        'last_lng' => 'decimal:8',
        'rating_avg' => 'decimal:2',
        'total_earned' => 'decimal:2',
        'last_location_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getSpecializationIdsAttribute(): array
    {
        if (!$this->specializations) {
            return [];
        }
        $decoded = json_decode($this->specializations, true);
        return is_array($decoded) ? $decoded : [];
    }
}
