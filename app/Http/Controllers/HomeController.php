<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Contact;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Delivery;
use App\Models\Advertisement;
use App\Models\Slide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $slides = Slide::where('status',1)->get()->take(3);
        $categories = Category::orderBy('name')->get();
        $sproducts = Product::whereNotNull('sale_price')->where('sale_price','<>','')->inRandomOrder()->get()->take(8);
        $fproducts = Product::where('featured',1)->get()->take(8);
        $ads = Advertisement::first();

        $products = Product::orderBy('created_at','DESC')->paginate(12);
        return view('index',compact('slides','categories','sproducts','fproducts','products','ads'));
    }
    public function about(){
        $categories = Category::orderBy('name')->get();
        return view('about',compact('categories'));
    }
    public function contact(){
        $categories = Category::orderBy('name')->get();
        return view('contact',compact('categories'));
    }
    public function contact_store(Request $request){
        $request->validate([
            'name' => 'required|max:100',
            'email' => 'required|email',
            'phone' => 'required|numeric|digits:11',
            'comment' => 'required'
        ]);

        $contact = new Contact();
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->phone = $request->phone;
        $contact->comment = $request->comment;
        $contact->save();
        return redirect()->back()->with('success','Your Message has been sent successfully!');
    }
    public function search(Request $request)
    {
        $query = $request->input('query');
        $results = Product::where('name','LIKE',"%{$query}%")->get()->take(8);
        return response()->json($results);
    }
    //review
    public function validates($product_slug){
        $product = Product::where('slug', $product_slug)->firstOrFail();
        if(!Auth::check()){
            return redirect()->route('login');
        }
        return view('user.send-review', compact('product'));
    }

    public function deliveryPolicy(){  
        $delivery = Delivery::first();
        return view('policy.delivery', compact('delivery'));
    }
    public function returnPolicy(){ 
        return view('policy.return');
    }
    public function privacyPolicy(){ 
        return view('policy.privacy');
    }
}
