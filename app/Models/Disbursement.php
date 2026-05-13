<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disbursement extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'case_id',
        'lawyer_id',
        'amount',
        'platform_cut',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'status',
        'iris_transfer_id',
        'notes',
        'processed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'platform_cut' => 'decimal:2',
        'created_at' => 'datetime',
        'processed_at' => 'datetime',
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
