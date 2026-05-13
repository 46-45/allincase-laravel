<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseLawyerOffer extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'case_id',
        'lawyer_id',
        'status',
        'sent_at',
        'responded_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'responded_at' => 'datetime',
    ];

    public function case()
    {
        return $this->belongsTo(LegalCase::class, 'case_id');
    }

    public function lawyer()
    {
        return $this->belongsTo(User::class, 'lawyer_id');
    }
}
