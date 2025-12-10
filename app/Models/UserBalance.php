<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserBalance extends Model
{
    use HasFactory;

    protected $table = 'user_balances';

    protected $fillable = [
        'user_id',
        'balance',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}