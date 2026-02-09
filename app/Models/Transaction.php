<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    // allow mass assignment for order_id
    protected $fillable = [
        'user_id',
        'order_id', // added to allow mass assignment
        'mode',
        'status',
        'transaction_id',
    ];
    

    // ensure status is always treated as integer
    protected $casts = [
        'status' => 'string',
    ];

    public function order(){
        return $this->belongsTo(Order::class);
    }
}
