<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function category(){
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function brand(){
        return $this->belongsTo(Brand::class, 'brand_id');
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function likedProducts()
    {
        // Assuming a many-to-many relationship (User <-> Product) 
        // using a pivot table like 'product_likes'
        return $this->belongsToMany(Product::class, 'product_likes');
    }

    /**
     * Check if the user has liked a specific product
     */
    public function isLikedBy(Product $product)
    {
        return $this->likedProducts()->where('product_id', $product->id)->exists();
    }
}
