<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialLinks extends Model
{
    protected $table = 'social_links';
    
    protected $fillable = [
        'facebook',
        'twitter',
        'instagram',
        'kakaotalk',
    ];
}
