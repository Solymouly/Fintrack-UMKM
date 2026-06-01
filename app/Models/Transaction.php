<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    // Field yang diizinkan untuk diisi dari form (keamanan)
    protected $fillable = [
        'user_id',
        'type',
        'category',
        'amount',
        'transaction_date',
        'description',
    ];

    // Ngasih tau sistem kalau 1 transaksi itu milik 1 user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}