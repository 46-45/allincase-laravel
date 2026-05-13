<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LegalCase extends Model
{
    protected $table = 'cases';

    protected $fillable = [
        'case_number',
        'client_id',
        'lawyer_id',
        'category_id',
        'meeting_lat',
        'meeting_lng',
        'meeting_address',
        'meeting_datetime',
        'meeting_sub_district',
        'meeting_city_district',
        'meeting_province',
        'current_radius_level',
        'radius_expanded_at',
        'lawyer_lat_on_accept',
        'lawyer_lng_on_accept',
        'distance_km',
        'base_price',
        'service_fee',
        'total_price',
        'status',
        'detail_notes',
        'payment_id',
        'payment_method',
        'paid_at',
        'completed_at',
        'cancelled_at',
    ];

    protected $casts = [
        'meeting_lat' => 'decimal:8',
        'meeting_lng' => 'decimal:8',
        'meeting_datetime' => 'datetime',
        'lawyer_lat_on_accept' => 'decimal:8',
        'lawyer_lng_on_accept' => 'decimal:8',
        'distance_km' => 'decimal:2',
        'base_price' => 'decimal:2',
        'service_fee' => 'decimal:2',
        'total_price' => 'decimal:2',
        'paid_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'radius_expanded_at' => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function lawyer()
    {
        return $this->belongsTo(User::class, 'lawyer_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function offers()
    {
        return $this->hasMany(CaseLawyerOffer::class, 'case_id');
    }

    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class, 'case_id');
    }

    public function review()
    {
        return $this->hasOne(Review::class, 'case_id');
    }

    public function disbursement()
    {
        return $this->hasOne(Disbursement::class, 'case_id');
    }
}
