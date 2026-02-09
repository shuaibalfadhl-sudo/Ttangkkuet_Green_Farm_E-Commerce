<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'mobile',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
        ];
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function hasDeliveredOrderForProduct($productId)
    {
        return $this->orders()->where('status', 'delivered')
                            ->whereHas('orderItems', function ($query) use ($productId) {
                                $query->where('product_id', $productId);
                            })->exists();
    }
    public function likedReviews()
    {
        return $this->belongsToMany(Review::class, 'review_likes');
    }

    /**
     * Check if the user has liked a specific review.
     * * @param Review $review
     * @return bool
     */
    public function isLikedBy(Review $review)
    {
        // Check if a record exists in the 'likedReviews' relationship 
        // that matches the ID of the given review.
        return $this->likedReviews()->where('review_id', $review->id)->exists();
    }
    public function notification()
    {
        return $this->hasOne(ProductNotification::class);
    }
}
