<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topup extends Model
{
    use HasFactory;

    protected $table = 'topups';

    protected $fillable = [
        'user_id',
        'amount',
        'status',
        'va_number',
    ];

    /**
     * Relasi ke tabel users
     * Satu topup dimiliki oleh satu user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

