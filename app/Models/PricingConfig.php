<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingConfig extends Model
{
    public $timestamps = false;

    protected $table = 'pricing_config';

    protected $fillable = [
        'base_price',
        'base_km',
        'price_per_km',
        'service_fee',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'base_km' => 'decimal:2',
        'price_per_km' => 'decimal:2',
        'service_fee' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}
