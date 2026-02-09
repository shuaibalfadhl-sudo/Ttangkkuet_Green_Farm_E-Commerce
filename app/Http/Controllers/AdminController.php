<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\BankInfo;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Contact;
use App\Models\ContactInfo;
use App\Models\Coupon;
use App\Models\Delivery;
use App\Models\Logo;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Slide;
use App\Models\SocialLinks;
use App\Models\Transaction;
use App\Models\User;
use App\Models\ProductNotification;
use App\Mail\AvailableProductsMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Events\ProductUpdated;
class AdminController extends Controller
{
    public function index(Request $request)
    {
        // 🔹 1. Filters
        $year = $request->get('year', date('Y')); // default current year
        $month = $request->get('month');         // optional month
        $filter = $request->get('filter', 'all'); // legacy filter options

        // 🔹 2. Build dynamic WHERE clause for dashboard totals
        $whereClauses = [];
        $bindings = [];

        if ($year) {
            $whereClauses[] = "YEAR(created_at) = ?";
            $bindings[] = $year;
        }
        if ($month) {
            $whereClauses[] = "MONTH(created_at) = ?";
            $bindings[] = $month;
        }

        if ($filter === 'week') {
            $whereClauses[] = "YEARWEEK(created_at, 1) = YEARWEEK(NOW(), 1)";
        } elseif ($filter === 'month' && !$month) {
            $whereClauses[] = "YEAR(created_at) = YEAR(NOW()) AND MONTH(created_at) = MONTH(NOW())";
        } elseif ($filter === 'year' && !$year) {
            $whereClauses[] = "YEAR(created_at) = YEAR(NOW())";
        }

        $where = count($whereClauses) ? 'WHERE ' . implode(' AND ', $whereClauses) : '';

        // 🔹 3. Recent orders (Eloquent, eager load orderItems)
        $ordersQuery = Order::query();

        if ($year) {
            $ordersQuery->whereYear('created_at', $year);
        }
        if ($month) {
            $ordersQuery->whereMonth('created_at', $month);
        }
        if ($filter === 'week') {
            $ordersQuery->whereRaw("YEARWEEK(created_at, 1) = YEARWEEK(NOW(), 1)");
        }

        $orders = $ordersQuery->with('orderItems')
                              ->orderBy('created_at', 'DESC')
                              ->take(10)
                              ->get();

        // 🔹 4. Dashboard totals
        $dashboardDatas = DB::select("
            SELECT 
                SUM(total) AS TotalAmount,
                SUM(IF(status='ordered', total, 0)) AS TotalOrderedAmount,
                SUM(IF(status='delivered', total, 0)) AS TotalDeliveredAmount,
                SUM(IF(status='canceled', total, 0)) AS TotalCanceledAmount,
                COUNT(*) AS Total,
                SUM(IF(status='ordered', 1, 0)) AS TotalOrdered,
                SUM(IF(status='delivered', 1, 0)) AS TotalDelivered,
                SUM(IF(status='canceled', 1, 0)) AS TotalCanceled
            FROM orders
            $where
        ", $bindings);

        // 🔹 5. Monthly breakdown (for chart) with parameter binding
        $monthlyQueryBindings = [$year];
        $monthCondition = '';
        if ($month) {
            $monthCondition = 'AND MONTH(created_at) = ?';
            $monthlyQueryBindings[] = $month;
        }

        $monthlydatas = DB::select("
            SELECT 
                M.id AS MonthNo, 
                M.name AS MonthName,
                IFNULL(D.TotalAmount, 0) AS TotalAmount,
                IFNULL(D.TotalOrderedAmount, 0) AS TotalOrderedAmount,
                IFNULL(D.TotalDeliveredAmount, 0) AS TotalDeliveredAmount,
                IFNULL(D.TotalCanceledAmount, 0) AS TotalCanceledAmount
            FROM month_names M
            LEFT JOIN (
                SELECT 
                    MONTH(created_at) AS MonthNo,
                    SUM(total) AS TotalAmount,
                    SUM(IF(status='ordered', total, 0)) AS TotalOrderedAmount,
                    SUM(IF(status='delivered', total, 0)) AS TotalDeliveredAmount,
                    SUM(IF(status='canceled', total, 0)) AS TotalCanceledAmount
                FROM orders
                WHERE YEAR(created_at) = ?
                $monthCondition
                GROUP BY MONTH(created_at)
            ) D ON D.MonthNo = M.id
        ", $monthlyQueryBindings);

        // 🔹 6. Prepare chart data arrays
        $AmountM = implode(',', collect($monthlydatas)->pluck('TotalAmount')->toArray());
        $orderedAmountM = implode(',', collect($monthlydatas)->pluck('TotalOrderedAmount')->toArray());
        $DeliveredAmountM = implode(',', collect($monthlydatas)->pluck('TotalDeliveredAmount')->toArray());
        $CanceledAmountM = implode(',', collect($monthlydatas)->pluck('TotalCanceledAmount')->toArray());

        // 🔹 7. Totals for display
        $TotalAmount = collect($monthlydatas)->sum('TotalAmount');
        $TotalOrderedAmount = collect($monthlydatas)->sum('TotalOrderedAmount');
        $TotalDeliveredAmount = collect($monthlydatas)->sum('TotalDeliveredAmount');
        $TotalCanceledAmount = collect($monthlydatas)->sum('TotalCanceledAmount');

        // 🔹 8. Other data
        $categories = Category::orderBy('name')->get();
        $mail = Contact::count();

        // 🔹 9. Pass all data to the view
        return view('admin.index', compact(
            'mail',
            'categories',
            'orders',
            'dashboardDatas',
            'AmountM',
            'orderedAmountM',
            'DeliveredAmountM',
            'CanceledAmountM',
            'TotalAmount',
            'TotalOrderedAmount',
            'TotalDeliveredAmount',
            'TotalCanceledAmount',
            'filter',
            'year',
            'month'
        ));
    }
    
//brand
    public function brands(){
        $message = Contact::all()->count();
        $brands = Brand::orderBy('id', 'DESC')->paginate(10);
        return view('admin.brands', compact('brands', 'message'));
    }
    public function add_brand(){
        $message = Contact::all()->count();
        return view('admin.brand-add', compact('message'));
    }
    public function brand_store(Request $request){
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug',
            'image' => 'mimes:png,jpg,jpeg|max:40960'
        ]);

        $brand = new Brand();
        $brand -> name = $request->name;
        $brand -> slug = Str::slug($request->name);
        $image = $request->file('image');
        $file_extention = $request-> file('image')->extension();
        $file_name = Carbon::now()->timestamp.'.'.$file_extention;
        $this -> GenerateBrandThumbnailsImage($image, $file_name);
        $brand -> image = $file_name;
        $brand -> save();

        return redirect()->route('admin.brands')->with('status', 'Brand has been added successfully!');
    } 
    public function brand_edit($id){
        $message = Contact::all()->count();
        $brand = Brand::find($id);
        return view('admin.brand-edit', compact('brand','message'));
    }
    public function brand_update(Request $request){
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug,'.$request->id,
            'image' => 'mimes:png,jpg,jpeg|max:40960'
        ]);

        $brand = Brand::find($request->id);
        $brand -> name = $request->name;
        $brand -> slug = Str::slug($request->name);
        if($request->hasFile('image')){
            if(File::exists(public_path('uploads/brands').'/'.$brand->image)){
                File::delete(public_path('uploads/brands').'/'.$brand->image);
            }
            $image = $request->file('image');
            $file_extention = $request-> file('image')->extension();
            $file_name = Carbon::now()->timestamp.'.'.$file_extention;
            $this -> GenerateBrandThumbnailsImage($image, $file_name);
            $brand -> image = $file_name;
        }
        
        $brand -> save();

        return redirect()->route('admin.brands')->with('status', 'Brand has been updated successfully!');
    }
    public function GenerateBrandThumbnailsImage($image, $imageName){
        $destination = public_path('uploads/brands');
        $img = Image::read($image->path());
        $img -> cover(124,124,"top");
        $img -> resize(124,124,function($constraint){
            $constraint->aspectRatio();
        })->save($destination.'/'.$imageName);
        
    }
    public function brand_delete($id){
        $brand = Brand::find($id);
        if(File::exists(public_path('uploads/brands').'/'.$brand->image)){
            File::delete(public_path('uploads/brands').'/'.$brand->image);
        }
        $brand->delete();
        return redirect()->route('admin.brands')->with('status','The brand has been successfully deleted!');
    }
// Category
    public function categories(Request $request){
        $message = Contact::all()->count();
        
        // Start with the base query
        $categoriesQuery = Category::orderBy('id', 'DESC');

        // Check for the search query passed from the GET form submission
        if ($request->has('search') && $request->input('search') != '') {
            $searchTerm = $request->input('search');
            
            // Filter the main table: search for the term anywhere in the name or slug
            $categoriesQuery->where('name', 'LIKE', '%' . $searchTerm . '%')
                            ->orWhere('slug', 'LIKE', '%' . $searchTerm . '%');
        }

        // Paginate the results (filtered or unfiltered)
        $categories = $categoriesQuery->paginate(10);
        
        return view('admin.categories', compact('message','categories'));
    }
    public function category_add(){
        $message = Contact::all()->count();
        return view('admin.category-add',compact('message'));
    }
    public function category_store(Request $request){
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug',
            'image' => 'mimes:png,jpg,jpeg|max:40960'
        ]);

        $category = new Category();
        $category -> name = $request->name;
        $category -> slug = Str::slug($request->name);
        $image = $request->file('image');
        $file_extention = $request-> file('image')->extension();
        $file_name = Carbon::now()->timestamp.'.'.$file_extention;
        $this -> GenerateCategoryThumbnailsImage($image, $file_name);
        $category -> image = $file_name;
        $category -> save();

        return redirect()->route('admin.categories')->with('status', 'Category has been added successfully!');
    }
    public function category_edit($id){
        $message = Contact::all()->count();
        $category = Category::find($id);
        return view('admin.category-edit', compact('category','message'));
    }
    public function category_update(Request $request){
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,'.$request->id,
            'image' => 'mimes:png,jpg,jpeg|max:40960'
        ]);

        $category = Category::find($request->id);
        $category -> name = $request->name;
        $category -> slug = Str::slug($request->name);
        if($request->hasFile('image')){
            if(File::exists(public_path('uploads/categories').'/'.$category->image)){
                File::delete(public_path('uploads/categories').'/'.$category->image);
            }
            $image = $request->file('image');
            $file_extention = $request-> file('image')->extension();
            $file_name = Carbon::now()->timestamp.'.'.$file_extention;
            $this -> GenerateCategoryThumbnailsImage($image, $file_name);
            $category -> image = $file_name;
        }
        
        $category -> save();

        return redirect()->route('admin.categories')->with('status', 'Category has been updated successfully!');
    }
    public function GenerateCategoryThumbnailsImage($image, $imageName){
        $destination = public_path('uploads/categories');
        $img = Image::read($image->path());
        $img -> cover(124,124,"top");
        $img -> resize(124,124,function($constraint){
            $constraint->aspectRatio();
        })->save($destination.'/'.$imageName);
    }
    public function category_delete($id){
        $category = Category::find($id);
        if(File::exists(public_path('uploads/categories').'/'.$category->image)){
            File::delete(public_path('uploads/categories').'/'.$category->image);
        }
        $category->delete();
        return redirect()->route('admin.categories')->with('status','The category has been successfully deleted!');
    }
//Product
    public function products(Request $request){ 
        $message = Contact::all()->count();
        
        // Start with the base query
        $productsQuery = Product::orderBy('created_at', 'DESC');

        // 1. Check if a search term exists in the request
        if ($request->has('search') && $request->input('search') != '') {
            $searchTerm = $request->input('search');
            
            // 2. Apply the search filter
            // We use 'LIKE' with wildcards (%) to find the term anywhere in the name or SKU.
            $productsQuery->where('name', 'LIKE', '%' . $searchTerm . '%')
                        ->orWhere('SKU', 'LIKE', '%' . $searchTerm . '%');
            
            // You can add more fields to search against here (e.g., category name, brand name, etc.)
        }

        // 3. Paginate the results (filtered or unfiltered)
        $products = $productsQuery->paginate(10);

        // Append the search term to the pagination links so the filter remains when changing pages
        $products->appends(['search' => $request->input('search')]);
        
        return view('admin.products', compact('products', 'message'));
    }
    public function product_add(){
        $message = Contact::all()->count();
        $categories = Category::select('id','name')->orderBy('name')->get();
        $brands = Brand::select('id','name')->orderBy('name')->get();
        return view('admin.product-add',compact('categories','brands','message'));
    }
    public function product_store(Request $request){
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:products,slug',
            'short_description' => 'required',
            'description' => 'required',
            'regular_price' => 'required',
            'sale_price' => 'nullable',
            'SKU' => 'required',
            'stock_status' => 'required',
            'featured' => 'required',
            'quantity' => 'required',
            'image' => 'required|mimes:png,jpg,jpeg|max:40960',
            'category_id' => 'required',
            'brand_id' => 'nullable',
        ]);

        $product = new Product();
        $product -> name = $request -> name;
        $product -> slug = Str::slug($request -> name);
        $product -> short_description = $request -> short_description;
        $product -> description = $request -> description;
        $product -> regular_price = floatval(str_replace(',', '', $request->regular_price));
        $product -> sale_price = floatval(str_replace(',', '', $request->sale_price));
        $product -> SKU = $request -> SKU;
        $product -> stock_status = $request -> stock_status;
        $product -> featured = $request -> featured;
        $product -> quantity = $request -> quantity;
        $product -> category_id = $request -> category_id;
        $product -> brand_id = $request -> brand_id;

        $current_timestamp = Carbon::now()->timestamp;
        if($request->hasFile('image')){
            $image = $request ->file('image');
            $imageName = $current_timestamp . '.' .$image->extension();
            $this -> GenerateProductThumbnailsImage($image, $imageName);
            $product -> image = $imageName;
        }
        $gallery_arr = array();
        $gallery_images = "";
        $counter = 1;

        if($request->hasFile('images')){
            $allowedfileExtention = ['jpg','png','jpeg'];
            $files = $request -> file('images');
            foreach($files as $file){
                $gextension = $file->getClientOriginalExtension();
                $gcheck = in_array($gextension, $allowedfileExtention);
                if($gcheck){
                    $gfileName = $current_timestamp . "-" . $counter . "." . $gextension;
                    $this->GenerateProductThumbnailsImage($file, $gfileName);
                    array_push($gallery_arr, $gfileName);
                    $counter = $counter + 1;
                }
            }
            $gallery_images = implode(',', $gallery_arr);
        }
        $product->images = $gallery_images;
        $product->save();
        return redirect()->route('admin.products')->with('status', 'Product has been added successfully!');
    }
    public function GenerateProductThumbnailsImage($image, $imageName){
        $destinationThumbnail = public_path('uploads/products/thumbnails');
        $destination = public_path('uploads/products');
        $img = Image::read($image->path());

        $img -> cover(540,689,"top");
        $img -> resize(540,689,function($constraint){
            $constraint->aspectRatio();
        })->save($destination.'/'.$imageName);

        $img -> resize(104,104,function($constraint){
            $constraint->aspectRatio();
        })->save($destinationThumbnail.'/'.$imageName);
    }
    public function product_edit($id){
        $message = Contact::all()->count();
        $product = Product::find($id);
        $categories = Category::select('id','name')->orderBy('name')->get();
        $brands = Brand::select('id','name')->orderBy('name')->get();
        return view ('admin.product-edit', compact('product','categories','brands','message'));
    }
    public function product_update(Request $request){
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:products,slug,'.$request->id,
            'short_description' => 'required',
            'description' => 'required',
            'regular_price' => 'required',
            'sale_price' => 'nullable',
            'SKU' => 'required',
            'stock_status' => 'required',
            'featured' => 'required',
            'quantity' => 'required',
            'image' => 'mimes:png,jpg,jpeg|max:40960',
            'category_id' => 'required',
            'brand_id' => 'nullable', 
        ]);
        $product = Product::find($request->id);

        $product -> name = $request -> name;
        $product -> slug = Str::slug($request -> name);
        $product -> short_description = $request -> short_description;
        $product -> description = $request -> description;
        $product -> regular_price = floatval(str_replace(',', '', $request->regular_price));
        $product -> sale_price = floatval(str_replace(',', '', $request->sale_price));
        $product -> SKU = $request -> SKU;
        $product -> stock_status = $request -> stock_status;
        $product -> featured = $request -> featured;
        $product -> quantity = $request -> quantity;
        $product -> category_id = $request -> category_id;
        $product -> brand_id = $request -> brand_id;

        $current_timestamp = Carbon::now()->timestamp;
        if($request->hasFile('image')){
            if(File::exists(public_path('uploads/products').'/'.$product->image)){
                File::delete(public_path('uploads/products').'/'.$product->image);
            }
            if(File::exists(public_path('uploads/products/thumbnails').'/'.$product->image)){
                File::delete(public_path('uploads/products/thumbnails').'/'.$product->image);
            }
            $image = $request ->file('image');
            $imageName = $current_timestamp . '.' .$image->extension();
            $this -> GenerateProductThumbnailsImage($image, $imageName);
            $product -> image = $imageName;
        }
        $gallery_arr = array();
        $gallery_images = "";
        $counter = 1;

        if($request->hasFile('images')){
            foreach(explode(',',$product->images)as $ofile){
                if(File::exists(public_path('uploads/products').'/'.$ofile)){
                    File::delete(public_path('uploads/products').'/'.$ofile);
                }
                if(File::exists(public_path('uploads/products/thumbnails').'/'.$ofile)){
                    File::delete(public_path('uploads/products/thumbnails').'/'.$ofile);
                }
            }
            $allowedfileExtention = ['jpg','png','jpeg'];
            $files = $request -> file('images');
            foreach($files as $file){
                $gextension = $file->getClientOriginalExtension();
                $gcheck = in_array($gextension, $allowedfileExtention);
                if($gcheck){
                    $gfileName = $current_timestamp . "-" . $counter . "." . $gextension;
                    $this->GenerateProductThumbnailsImage($file, $gfileName);
                    array_push($gallery_arr, $gfileName);
                    $counter = $counter + 1;
                }
            }
            $gallery_images = implode(',', $gallery_arr);
            $product->images = $gallery_images;
        }
        
        $product->save();
        event(new ProductUpdated($product));
        return redirect()->route('admin.products')->with('status', 'Product has been updated successfully!');
    }
    public function product_delete($id){
        $product = Product::find($id);
        
        if(File::exists(public_path('uploads/products').'/'.$product->image)){
            File::delete(public_path('uploads/products').'/'.$product->image);
        }
        if(File::exists(public_path('uploads/products/thumbnails').'/'.$product->image)){
            File::delete(public_path('uploads/products/thumbnails').'/'.$product->image);
        }
        foreach(explode(',',$product->images)as $ofile){
            if(File::exists(public_path('uploads/products').'/'.$ofile)){
                File::delete(public_path('uploads/products').'/'.$ofile);
            }
            if(File::exists(public_path('uploads/products/thumbnails').'/'.$ofile)){
                File::delete(public_path('uploads/products/thumbnails').'/'.$ofile);
            }
        }
        $product->delete();
        return redirect()->route('admin.products')->with('status', 'Product has been deleted successfully!');
    }
    //MAIL UPDATE TO ALL SUBSCRIBERS
    public function sendAllUpdates(){
        $availableProducts = Product::where('quantity', '>', 0)->get(); 

        if ($availableProducts->isEmpty()) {
            return back()->with('info', 'No products are currently in stock.');
        }

        // Load all subscribers with their user relationship
        $subscribers = ProductNotification::where('receive_updates', true)
            ->with('user')
            ->get()
            ->pluck('user') // extract the actual User models
            ->filter(); // remove nulls just in case

        if ($subscribers->isEmpty()) {
            return back()->with('info', 'No users have enabled notifications.');
        }

        foreach ($subscribers as $user) {
            if ($user && $user->email) {
                Mail::to($user->email)->send(new AvailableProductsMail($availableProducts));
            }
        }

        return back()->with('success', 'Emails sent to all subscribed users with current available products.');
    }

//coupons
    public function coupons(){
        $message = Contact::all()->count();
        $coupons = Coupon::orderBy('expiry_date','DESC')->paginate(12);
        return view('admin.coupons',compact('coupons','message'));
    }
    public function coupon_add(){
        $message = Contact::all()->count();
        return view('admin.coupon-add',compact('message'));
    }
    public function coupon_store(Request $request){
        $request->validate([
            'code' => 'required',
            'type' => 'required',
            'value' => 'required|numeric',
            'cart_value' => 'required|numeric',
            'expiry_date' => 'required|date'
        ]);
        $coupon = new Coupon();
        $coupon->code = $request->code;
        $coupon->type = $request->type;
        $coupon->value = $request->value;
        $coupon->cart_value = $request->cart_value;
        $coupon->expiry_date = $request->expiry_date;
        $coupon->save();
        return redirect()->route('admin.coupons')->with('status', 'Coupon has been added successfully!');
    }
    public function coupon_edit($id){
        $message = Contact::all()->count();
        $coupon = Coupon::find($id);
        return view('admin.coupon-edit',compact('coupon','message'));
    }
    public function coupon_update(Request $request){
        $request->validate([
            'code' => 'required|unique:coupons,code,'.$request->id,
            'type' => 'required',
            'value' => 'required|numeric',
            'cart_value' => 'required|numeric',
            'expiry_date' => 'required|date'
        ]);
        $coupon = Coupon::find($request->id);
        $coupon->code = $request->code;
        $coupon->type = $request->type;
        $coupon->value = $request->value;
        $coupon->cart_value = $request->cart_value;
        $coupon->expiry_date = $request->expiry_date;
        $coupon->save();
        return redirect()->route('admin.coupons')->with('status', 'Coupon has been updated successfully!');
    }
    public function coupon_delete($id){
        $coupon = Coupon::find($id);
        $coupon->delete(); 
        return redirect()->route('admin.coupons')->with('status', 'Coupon has been deleted successfully!');
    }
    //order
    public function orders(Request $request){
        $message = Contact::all()->count();
        
        // Start with the base query
        $ordersQuery = Order::with('transaction')->orderBy('created_at', 'DESC');

        // 1. Search Filter (by Order ID) - (This is fine as 'id' is on the orders table)
        if ($request->has('search') && $request->input('search') != '') {
            $searchTerm = $request->input('search');
            $ordersQuery->where('id', 'LIKE', '%' . $searchTerm . '%');
        }

        // 2. Status Filter (This is fine assuming 'status' is on the orders table)
        if ($request->has('status') && $request->input('status') != '') {
            $ordersQuery->where('status', $request->input('status'));
        }

            if ($request->filled('transaction_status')) {
        $ordersQuery->whereHas('transaction', function($q) use ($request) {
            $q->where('status', $request->transaction_status);
        });
        }
        // 3. 🚨 CORRECTED: Payment Mode Filter using whereHas 🚨
        if ($request->has('payment_mode') && $request->input('payment_mode') != '') {
            $paymentMode = $request->input('payment_mode');
            
            $ordersQuery->whereHas('transaction', function ($q) use ($paymentMode) {
                // Check the 'mode' column in the related 'transactions' table
                $q->where('mode', $paymentMode); 
            });
        }

        // Paginate the results (filtered or unfiltered)
        $orders = $ordersQuery->paginate(12);

        return view('admin.orders', compact('orders', 'message'));
    }
    public function order_details($order_id){
        $message = Contact::all()->count();
        $order = Order::find($order_id);
        $orderItems = OrderItem::where('order_id',$order_id)->orderBy('id')->paginate(12);
        $transaction = Transaction::where('order_id',$order_id)->first();
        return view('admin.order-details', compact('order','orderItems', 'transaction','message'));
    }
    public function update_order_status(Request $request){
        $order = Order::find($request->order_id);
        $order->status = $request->order_status;
        if($request->order_status == 'delivered'){
            $order->delivered_date = Carbon::now();
        }else if($request->order_status == 'canceled'){
            $order->canceled_date = Carbon::now();
        }
        $order->save();
        
        if($request->order_status == 'delivered'){
            $transaction = Transaction::where('order_id',$request->order_id)->first();
            $transaction->status = 'approved';
            $transaction->save();
        }
        return back()->with("status","Status Changed Successfully!");
    }
    public function order_delete($order_id){
        $message = Contact::all()->count();
        $order = Order::find($order_id);
        $order->delete();
        return redirect()->route('admin.orders', compact('order','message'))->with('status', 'Order has been deleted successfully!');
    }
    public function markAsShipped(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'tracking_number' => 'required|string|max:255', // Validate the new field
        ]);
        
        $order = Order::findOrFail($request->order_id);
        
        // 1. Update status
        $order->status = 'shipped';
        
        // 2. Save the tracking number
        $order->tracking_number = $request->tracking_number; 
        
        $order->save();
        
        return back()->with("status", "Order marked as shipped and tracking number added successfully!");
    }
    public function markAsDelivered(Request $request)
    {
        // 1. Basic validation to ensure the order_id exists
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        // 2. Find the order
        $order = Order::findOrFail($request->order_id);
        
        // 3. Optional: Add a check to prevent marking as delivered if status is wrong
        if ($order->status != 'shipped') {
            Session::flash('error', 'Cannot mark order #' . $order->id . ' as delivered. Status is currently ' . $order->status . '.');
            return redirect()->back();
        }

        // 4. Update the status and delivered date
        $order->status = 'delivered';
        $order->delivered_date = now(); // Use now() to set the current timestamp
        $order->save();

        // 5. Optional: Handle Transaction Update (e.g., if it was a COD order)
        if ($order->transaction && $order->transaction->mode === 'cod') {
            $order->transaction->status = 'approved';
            $order->transaction->save();
        }

        // 6. Redirect with a success message
        Session::flash('success', 'Order #' . $order->id . ' has been successfully marked as delivered.');
        return back()->with("status", "Order marked as delivered successfully!");
    }
    //home
    public function slides(){
        $message = Contact::all()->count();
        $slides = Slide::orderBy('id','DESC')->paginate(12);
        return view('admin.slides',compact('slides','message'));
    }
    public function slide_add(){
        $message = Contact::all()->count();
        return view('admin.slide-add',compact('message'));
    }
    public function slide_store(Request $request){
        $request->validate([
            'tagline' => 'required',
            'title' => 'required',
            'subtitle' => 'required',
            'link' => 'required',
            'status' => 'required|integer',
            'image' => 'required|mimes:png,jpg,jpeg|max:40960',
            'bgimage' => 'required|mimes:png,jpg,jpeg|max:40960'
        ]);
        $slide = new Slide();
        $slide->tagline = $request->tagline;
        $slide->title = $request->title;
        $slide->subtitle = $request->subtitle;
        $slide->link = $request->link;
        $slide->status = $request->status;
        
        $image = $request->file('image');
        $file_extention = $request->file('image')->extension();
        $file_name = Carbon::now()->timestamp.'.'.$file_extention;
        $this->GenerateSlideThumbnailsImage($image,$file_name);
        $slide->image = $file_name;

        $bgimage = $request->file('bgimage');
        $file_extention = $request->file('bgimage')->extension();
        $file_name = Carbon::now()->timestamp.'.'.$file_extention;
        $this->GenerateSlideBackgroundImage($bgimage,$file_name);
        $slide->bgimage = $file_name;
    
        $slide->save();
        return redirect()->route('admin.slides')->with("status","Slide added successfully!");
    }
    public function GenerateSlideThumbnailsImage($image, $imageName)
    {
        $destination = public_path('uploads/slides');
        $img = Image::read($image->path());
        $img -> cover(400,690,"top");
        $img -> resize(400,690,function($constraint){
            $constraint->aspectRatio();
        })->save($destination.'/'.$imageName);
    }
    public function GenerateSlideBackgroundImage($bgimage, $bgImageName)
    {
        $destination = public_path('uploads/slides/bgimage'); 
        $img = Image::read($bgimage->path());
        $img->resize(1920, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->save($destination . '/' . $bgImageName);
    }
    public function slide_edit($id){
        $message = Contact::all()->count();
        $slide = Slide::find($id);
        return view('admin.slide-edit',compact('message','slide'));
    }
    public function slide_update(Request $request){
        $request->validate([
            'tagline' => 'required',
            'title' => 'required',
            'subtitle' => 'required',
            'link' => 'required',
            'status' => 'required',
            'image' => 'mimes:png,jpg,jpeg|max:40960',
            'bgimage' => 'mimes:png,jpg,jpeg|max:40960'
        ]);
        $slide = Slide::find($request->id);
        $slide->tagline = $request->tagline;
        $slide->title = $request->title;
        $slide->subtitle = $request->subtitle;
        $slide->link = $request->link;
        $slide->status = $request->status;
        
        if($request->hasFile('image')){
            if(File::exists(public_path('uploads/slides').'/'.$slide->image)){
                File::delete(public_path('uploads/slides').'/'.$slide->image);
            }
            $image = $request->file('image');
            $file_extention = $request->file('image')->extension();
            $file_name = Carbon::now()->timestamp.'.'.$file_extention;
            $this->GenerateSlideThumbnailsImage($image,$file_name);
            $slide->image = $file_name;
        }
        if ($request->hasFile('bgimage')) {
        if ($slide->bgimage && File::exists(public_path('uploads/slides/bgimage') . '/' . $slide->bgimage)) {
            File::delete(public_path('uploads/slides/bgimage') . '/' . $slide->bgimage);
        }
        $bgimage = $request->file('bgimage');
        $bg_file_extension = $request->file('bgimage')->extension();
        $bg_file_name = 'bg_' . Carbon::now()->timestamp . '.' . $bg_file_extension;
        $this->GenerateSlideBackgroundImage($bgimage, $bg_file_name);
        $slide->bgimage = $bg_file_name;
        }
        $slide->save();
        return redirect()->route('admin.slides')->with("status","Slide updated successfully!");
    }
    public function slide_delete($id){
        $slide = Slide::find($id);
        if(File::exists(public_path('uploads/slides').'/'.$slide->image)){
            File::delete(public_path('uploads/slides').'/'.$slide->image);
        }
        if ($slide->bgimage && File::exists(public_path('uploads/slides/bgimage') . '/' . $slide->bgimage)) {
            File::delete(public_path('uploads/slides') . '/' . $slide->bgimage);
        }
        $slide->delete();
        return redirect()->route('admin.slides')->with("status","Slide deleted successfully!");
    }
    //contact
    public function contacts(){
        $message = Contact::all()->count();
        $contacts = Contact::orderBy('created_at','DESC')->paginate(10);
        return view('admin.contacts',compact('contacts','message'));
    }
    public function contact_delete($id){
        $contact = Contact::find($id);
        $contact->delete();
        return redirect()->route('admin.contacts')->with("status","Contact deleted successfully!");
    }
    //search
    public function search(Request $request){
        $query = $request->input('query');
        $results = Product::where('name','LIKE',"%{$query}%")->get()->take(8);
        return response()->json($results);
    }
    public function liveSearch(Request $request)
    {
        $query = $request->get('query');

        // Filter products whose name STARTS WITH the search query
        // This implements the "preview start showing at first letter" requirement
        $products = Product::where('name', 'like', '%' . $query . '%')
                        ->limit(10) // Limit results for a cleaner dropdown
                        ->get(['id', 'name']);

        return response()->json($products);
    }
    public function liveSearchCategory(Request $request)
    {
        $query = $request->get('query');

        if (empty($query)) {
            return response()->json([]);
        }

        // Live Search: searches for the query anywhere in the category name, limited to 15 results
        $categories = Category::where('name', 'like', '%' . $query . '%')
                        ->limit(15) // Enlarged result limit
                        ->get(['id', 'name']); // Only select necessary columns

        return response()->json($categories);
    }
    public function liveSearchOrder(Request $request)
    {
        $query = $request->get('query');

        if (empty($query)) {
            return response()->json([]);
        }

        // Live Search: searches for the query in the Order ID
        // Note: Since ID is usually an integer, using LIKE on the string representation is common for partial ID search.
        $orders = Order::where('id', 'like', '%' . $query . '%')
                    ->limit(15) // Limit results for a cleaner dropdown
                    ->get(['id', 'name', 'phone']); // Get name/phone for a useful preview

        return response()->json($orders);
    }
    public function liveSearchUser(Request $request)
    {
        $query = $request->get('query');

        if (empty($query)) {
            return response()->json([]);
        }

        // Live Search: searches by name or email
        $users = User::where('name', 'like', '%' . $query . '%')
                    ->orWhere('email', 'like', '%' . $query . '%')
                    ->limit(15)
                    ->get(['id', 'name', 'email']);

        return response()->json($users);
    }
    //print
    public function print($order_id){
        $order = Order::find($order_id);
        $pdf = Pdf::loadView('admin.invoice', compact('order'));
        return $pdf->download('invoice.pdf');
    }
    public function downloadMonthlyReport(Request $request)
    {
        $year = $request->get('year');
        $month = $request->get('month');
        $query = Order::query();
        if ($year) {
            $query->whereYear('created_at', $year);
        }
        if ($month) {
            $query->whereMonth('created_at', $month);
        }
        $orders = $query->get();

        if ($orders->isEmpty()) {
            return back()->with('error', 'No order data found for the selected filter(s).');
        }
        $metrics = [
            'total_orders'       => $orders->count(),
            'total_amount'       => $orders->sum('total'),
            'delivered_orders'   => $orders->where('status', 'delivered')->count(),
            'delivered_amount'   => $orders->where('status', 'delivered')->sum('total'),
            'pending_orders'     => $orders->where('status', 'ordered')->count(),
            'pending_amount'     => $orders->where('status', 'ordered')->sum('total'),
            'canceled_orders'    => $orders->where('status', 'canceled')->count(),
            'canceled_amount'    => $orders->where('status', 'canceled')->sum('total'),
        ];
        
        
        $dateParts = [];
        if ($month) {
            $dateParts[] = \DateTime::createFromFormat('!m', $month)->format('F');
        }
        if ($year) {
            $dateParts[] = $year;
        }
        $dateLabel = empty($dateParts) ? 'All Time' : implode(' ', $dateParts);
        
        $pdf = PDF::loadView('admin.sale', compact('metrics', 'dateLabel'));
        
        $filename = 'Order_Report_' . str_replace(' ', '_', $dateLabel) . '.pdf';
        
        return $pdf->download($filename);
    }
    //users 
    public function users(Request $request){
        // Assuming Contact model is defined
        $message = Contact::all()->count(); 

        // Start with the base query, eager load the orders count
        $usersQuery = User::withCount('orders');

        // 1. --- Apply Search Filter (Name or Email) ---
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            
            $usersQuery->where(function ($query) use ($searchTerm) {
                $query->where('name', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('email', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        // 2. --- Apply User Type (utype) Filter ---
        if ($request->filled('utype')) {
            $usersQuery->where('utype', $request->input('utype'));
        }

        // 3. --- Apply Sorting Logic (FIXED for Last Login/NULLs) ---
        $sort = $request->input('sort', 'id_desc'); // Default sort: Newest Users by ID

        switch ($sort) {
            case 'login_desc':
                // FIX: Most Recent Login first, pushing NULLs (Never Logged In) to the end.
                // last_login_at IS NULL returns 0 (false) for non-nulls and 1 (true) for nulls.
                // Sorting by this boolean first pushes the 1s (NULLs) to the bottom.
                $usersQuery->orderByRaw('last_login_at IS NULL, last_login_at DESC');
                break;
                
            case 'login_asc':
                // FIX: Least Recent Login first, pushing NULLs (Never Logged In) to the end.
                $usersQuery->orderByRaw('last_login_at IS NULL, last_login_at ASC');
                break;
                
            case 'orders_desc':
                // Sort by total orders (High to Low)
                $usersQuery->orderBy('orders_count', 'desc'); 
                break;
                
            case 'id_desc':
            default:
                // Default sort: Newest Users by ID
                $usersQuery->orderBy('id', 'desc');
                break;
        }

        // Paginate the results (filtered and sorted)
        // Use appends() to keep the search/filter/sort parameters in the pagination links
        $users = $usersQuery->paginate(12)->appends($request->query());
        
        return view('admin.user', compact('users', 'message'));
    }
    public function editUserRole($id)
    {
        $user = User::findOrFail($id);
        $message = Contact::all()->count();
        
        // In a real application, you might use a dedicated Blade view for this, 
        // but for simplicity, imagine a view named 'admin.edit_user_role'
        return view('admin.edit-user-role', compact('user', 'message'));
    }
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        // Security check: Prevent admin from deleting their own account
        if (Auth::id() == $user->id) {
            return redirect()->route('admin.users')->with('status', 'Error: You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users')->with('status', 'User "' . $user->name . '" deleted successfully.');
    }
//Settings
    public function settings(){ 
        $message = Contact::all()->count();
        $ads = Advertisement::first();
        $logo = Logo::first();
        $delivery = Delivery::first();
        $contactInfo = ContactInfo::first();
        $bankInfo = BankInfo::first();
        $socialLinks = SocialLinks::first();
        return view('admin.settings',compact('message','ads','logo','delivery','contactInfo','bankInfo','socialLinks'));
    }
    public function advertisement_update(Request $request)
    {
        $request->validate([
            'image' => 'required|mimes:png,jpg,jpeg|max:40960',
        ]);

        // ✅ Get the existing advertisement (or create new if none)
        $ads = Advertisement::first();

        if (!$ads) {
            $ads = new Advertisement();
        }

        if ($request->hasFile('image')) {
            // 🧹 Delete old image if exists
            if ($ads->image && File::exists(public_path('uploads/ads/' . $ads->image))) {
                File::delete(public_path('uploads/ads/' . $ads->image));
            }

            // 🆕 Process and save new image
            $image = $request->file('image');
            $file_extention = $image->extension();
            $file_name = Carbon::now()->timestamp . '.' . $file_extention;

            // Generate resized image (656x656)
            $this->GenerateAdvertisementThumbnailsImage($image, $file_name);

            $ads->image = $file_name;
        }

        $ads->save();

        return redirect()->route('admin.settings')->with('status', 'Advertisement image updated successfully!');
    }
    public function GenerateAdvertisementThumbnailsImage($image, $imageName)
    {
        $destination = public_path('uploads/ads');

        // 🖼️ Resize & crop image to 656x656 pixels
        $img = Image::read($image->path())
            ->cover(656, 656, 'center') // ensures exact 656x656 size by cropping
            ->save($destination . '/' . $imageName, 90); // 90% quality
    }
    public function logoUpdate(Request $request)
    {
        $request->validate([
            'main_logo' => 'nullable|mimes:png,jpg,jpeg|max:40960',
            'sub_logo'  => 'nullable|mimes:png,jpg,jpeg|max:40960',
        ]);

        // ✅ Always fetch existing record (there should only be one)
        $logo = Logo::first() ?? new Logo();

        // =============================
        // 🖼️ MAIN LOGO (486x177)
        // =============================
        if ($request->hasFile('main_logo')) {
            $mainDestination = public_path('uploads/logo/main');

            // Delete old main logo
            if (!empty($logo->main_logo) && File::exists($mainDestination . '/' . $logo->main_logo)) {
                File::delete($mainDestination . '/' . $logo->main_logo);
            }

            // Save new file
            $mainImage = $request->file('main_logo');
            $mainFileName = Carbon::now()->timestamp . '.' . $mainImage->extension();

            $this->GenerateLogoThumbnailsImage($mainImage, $mainFileName, $mainDestination, 486, 177);

            $logo->main_logo = $mainFileName;
        }

        // =============================
        // 🖼️ SUB LOGO (60x60)
        // =============================
        if ($request->hasFile('sub_logo')) {
            $subDestination = public_path('uploads/logo/sub');

            // Delete old sub logo
            if (!empty($logo->sub_logo) && File::exists($subDestination . '/' . $logo->sub_logo)) {
                File::delete($subDestination . '/' . $logo->sub_logo);
            }

            // Save new file
            $subImage = $request->file('sub_logo');
            $subFileName = 'sub_' . Carbon::now()->timestamp . '.' . $subImage->extension();

            $this->GenerateLogoThumbnailsImage($subImage, $subFileName, $subDestination, 60, 60);

            $logo->sub_logo = $subFileName;
        }

        $logo->save();

        return redirect()->route('admin.settings')->with('status', 'Logo updated successfully!');
    } 
    public function GenerateLogoThumbnailsImage($image, $imageName, $destination, $width, $height)
    {
        if (!File::exists($destination)) {
            File::makeDirectory($destination, 0755, true);
        }

        // Resize and crop the image to exact size
        Image::read($image->path())
            ->cover($width, $height, 'center') // ensures exact size by cropping
            ->save($destination . '/' . $imageName, 90); // 90% quality
    }
    public function deliveryChargeUpdate(Request $request)
    {
        $request->validate([
            'delivery_fee' => 'required|numeric|min:0',
            'minimum_order_amount' => 'nullable|numeric|min:0',
        ]);

        $delivery = Delivery::first() ?? new Delivery();
        $delivery->delivery_fee = $request->delivery_fee;
        $delivery->minimum_order_amount = $request->minimum_order_amount;
        $delivery->save();

        return redirect()->route('admin.settings')->with('status', 'Delivery charge updated successfully!');
    }
    public function contactInfoUpdate(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:500',
        ]);

        $contactInfo = ContactInfo::first() ?? new ContactInfo();
        $contactInfo->phone = $request->phone;
        $contactInfo->email = $request->email;
        $contactInfo->address = $request->address;
        $contactInfo->save();

        return redirect()->route('admin.settings')->with('status', 'Contact information updated successfully!');
    }
    public function bankInfoUpdate(Request $request)
    {
        $request->validate([
            'bank_name_one' => 'required|string|max:255',
            'account_number_one' => 'required|string|max:50',
            'account_holder_one' => 'required|string|max:20',
            'bank_name_two' => 'nullable|string|max:255',
            'account_number_two' => 'nullable|string|max:50',
            'account_holder_two' => 'nullable|string|max:20',
        ]);

        $bankInfo = BankInfo::first() ?? new BankInfo();
        $bankInfo->bank_name_one = $request->bank_name_one;
        $bankInfo->account_number_one = $request->account_number_one;
        $bankInfo->account_holder_one = $request->account_holder_one;
        $bankInfo->bank_name_two = $request->bank_name_two;
        $bankInfo->account_number_two = $request->account_number_two;
        $bankInfo->account_holder_two = $request->account_holder_two;
        $bankInfo->save();

        return redirect()->route('admin.settings')->with('status', 'Bank information updated successfully!');
    }
    public function socialLinksUpdate(Request $request)
    {
        $request->validate([
            'facebook' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'kakaotalk' => 'nullable|url|max:255',
        ]);
        $socialLinks = SocialLinks::first() ?? new SocialLinks();
        $socialLinks->facebook = $request->facebook;
        $socialLinks->twitter = $request->twitter;
        $socialLinks->instagram = $request->instagram;
        $socialLinks->kakaotalk = $request->kakaotalk;
        $socialLinks->save();
        
        return redirect()->route('admin.settings')->with('status', 'Social links updated successfully!');
    }
// 2. Handle the update submission
    public function updateUserRole(Request $request, $id)
    {
        $request->validate([
            'utype' => 'required|in:ADM,USR,RDR', // Validate against allowed types
        ]);

        $user = User::findOrFail($id);
        $user->utype = $request->utype;
        $user->save();

        return redirect()->route('admin.users')->with('status', 'User role updated successfully!');
    }
}
