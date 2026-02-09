<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Surfsidemedia\Shoppingcart\Facades\Cart;

class WishlistController extends Controller
{
    public function index(){
        $categories = Category::orderBy('name')->get();
        $items = Cart::instance('wishlist')->content();
        return view('wishlist',compact('items','categories'));
    }
    public function add_to_wishlist(Request $request){
        Cart::instance('wishlist')->add($request->id,$request->name,$request->quantity, $request->price)->associate('App\Models\Product');
        
        if (Auth::check()) {
            Cart::instance('wishlist')->store(Auth::id());
        }

        return redirect()->back();
    }
    public function remove_item($rowId){
        Cart::instance('wishlist')->remove($rowId);

        if (Auth::check()) {
            Cart::instance('wishlist')->store(Auth::id());
        }

        return redirect()->back();
    }
    public function empty_wishlist(){
        Cart::instance('wishlist')->destroy();

        if (Auth::check()) {
            Cart::instance('wishlist')->store(Auth::id());
        }

        return redirect()->back();
    }
    public function increase_wishlist_quantity($rowId){
        $product = Cart::instance('wishlist')->get($rowId);
        $qty = $product->qty + 1;
        Cart::instance('wishlist')->update($rowId,$qty);
        return redirect()->back();
    }
    public function decrease_wishlist_quantity($rowId){
        $product = Cart::instance('wishlist')->get($rowId);
        $qty = $product->qty - 1;
        Cart::instance('wishlist')->update($rowId,$qty);
        return redirect()->back();
    }
    public function move_to_cart($rowId){
        $item = Cart::instance('wishlist')->get($rowId);
        Cart::instance('wishlist')->remove($rowId);
        Cart::instance('cart')->add($item->id,$item->name,$item->qty, $item->price)->associate('App\Models\Product');

        if (Auth::check()) {
            Cart::instance('wishlist')->store(Auth::id());
            Cart::instance('cart')->store(Auth::id());
        }

        return redirect()->route('cart.index');
    }
}
