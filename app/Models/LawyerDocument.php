<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LawyerDocument extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'lawyer_id',
        'title',
        'file_url',
        'file_type',
        'file_size',
    ];

    public function lawyer()
    {
        return $this->belongsTo(User::class, 'lawyer_id');
    }
}
