<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductNotification extends Model
{
    protected $table = 'product_notifications';
    protected $fillable = [
        'user_id',
        'receive_updates',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
     public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
