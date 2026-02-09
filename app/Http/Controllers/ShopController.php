<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\Slide;
use App\Models\Advertisement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    public function index(Request $request){
        $size = $request->query('size', 12);
        $order = $request->query('order', -1);
        $f_brands = $request->query('brands');
        $f_categories = $request->query('categories');

        $o_column = 'id';
        $o_order = 'DESC';
        
        switch ($order) {
            case 1:
                $o_column = 'created_at';
                $o_order = 'DESC';
                break;
            case 2:
                $o_column = 'created_at';
                $o_order = 'ASC';
                break;
            case 3:
                $o_column = 'sale_price';
                $o_order = 'ASC';
                break;
            case 4:
                $o_column = 'sale_price';
                $o_order = 'DESC';
                break;
        }

        $query = Product::query();

        if ($f_brands) {
            $query->whereIn('brand_id', explode(',', $f_brands));
        }

        if ($f_categories) {
            $query->whereIn('category_id', explode(',', $f_categories));
        }

        if ($request->filled('query')) {
            $query->where('name', 'like', '%' . $request->query('query') . '%');
        }

        $products = $query->orderBy($o_column, $o_order)->paginate($size);

        $brands = Brand::orderBy('name', 'ASC')->get();
        $categories = Category::orderBy('name', 'ASC')->get();
        $slides = Slide::where('status', 1)->get()->take(3);
        $ads = Advertisement::first();
    
        return view('shop', compact('products', 'size', 'order', 'brands', 'f_brands', 'categories', 'f_categories','slides','ads'));
    }
    public function product_details($product_slug){
        $product = Product::where('slug',$product_slug)
                                ->with(['reviews.user'])
                                ->firstOrFail();
        $userReview = Review::where('user_id', Auth::id())
                            ->where('product_id', $product->id)
                            ->first();
        $topReviews = Review::where('product_id', $product->id)
                            ->withCount('likes')
                            ->orderBy('rating', 'desc')
                            ->limit(3)
                            ->get();
        $categories = Category::orderBy('name', 'ASC')->get();
        $medianRating = 0;
        if ($product->reviews->isNotEmpty()) {
            $ratings = $product->reviews->pluck('rating')->toArray();
            sort($ratings);
            $count = count($ratings);
            $middle = floor(($count - 1) / 2);
            if ($count % 2) {
                $medianRating = $ratings[$middle];
            } else {
                $lowMiddle = $ratings[$middle];
                $highMiddle = $ratings[$middle + 1];
                $medianRating = ($lowMiddle + $highMiddle) / 2;
            }
        }
        
        $rproducts = Product::where('slug','<>', $product_slug)->get()->take(8);
        return view('details',compact('product','rproducts','userReview','medianRating','categories','topReviews'));
    }
    public function product_reviews(Request $request, $product_slug)
    {
        $product = Product::where('slug', $product_slug)->firstOrFail();

        $reviewsQuery = $product->reviews();

        // Filter by rating
        if ($request->filled('rating') && in_array($request->rating, [1, 2, 3, 4, 5])) {
            $reviewsQuery->where('rating', $request->rating);
        }

        // Sort order
        $sort = $request->input('sort', 'newest');
        if ($sort === 'oldest') {
            $reviewsQuery->orderBy('created_at', 'asc');
        } else {
            $reviewsQuery->orderBy('created_at', 'desc');
        }

        // Eager load user and likes count, then paginate
        $reviews = $reviewsQuery->with('user')->withCount('likes')->paginate(10)->withQueryString();

        return view('reviews.index', [
            'product' => $product,
            'reviews' => $reviews
        ]);
    }
}
