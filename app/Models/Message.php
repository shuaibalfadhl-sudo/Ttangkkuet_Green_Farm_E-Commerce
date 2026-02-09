<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'attachment',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    // Sender user
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Receiver user
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    // Scope: unread messages
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    // Scope: between two user ids (either direction)
    public function scopeBetweenUsers($query, $userA, $userB)
    {
        return $query->where(function ($q) use ($userA, $userB) {
            $q->where('sender_id', $userA)->where('receiver_id', $userB);
        })->orWhere(function ($q) use ($userA, $userB) {
            $q->where('sender_id', $userB)->where('receiver_id', $userA);
        });
    }
}
