<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\BankInfo;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Review;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use App\Models\ReviewLike;
use App\Models\User;
use App\Models\ProductNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class Usercontroller extends Controller
{
    public function index(){
        $categories = Category::orderBy('name')->get();
        return view('user.index',compact('categories'));
    }
    public function password(){ 
        return view('user.password');
    }
    public function updatePassword(Request $request){
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed', // new_password_confirmation required automatically
        ]);

        $user = Auth::user();

        // Check if current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Your current password is incorrect.');
        }

        // Update to new password
        $user->password = Hash::make($request->new_password);
        /** @var \App\Models\User $user */
        $user->save();

        return back()->with('success', 'Password updated successfully.');
    }
    public function updateProfile(Request $request){  
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' =>  'required|max:20',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        $user = User::findOrFail(Auth::id());
        //Handle profile image upload
        if ($request->hasFile('profile_image')) {
            if (File::exists(public_path('uploads/profile_images/' . $user->profile_image))) {
                File::delete(public_path('uploads/profile_images/' . $user->profile_image));
            }
            $file = $request->file('profile_image');
            $fileName = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();

            $destination = public_path('uploads/profile_images');
            if (!File::exists($destination)) {
                File::makeDirectory($destination, 0755, true);
            }

            //Store new image inside storage/app/public/profile_images
            $file->move($destination, $fileName); 
            $user->profile_image = $fileName;
        }

        //Update other user info
        $user->name = $request->input('name');
        $user->mobile = $request->input('mobile');
        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
    public function orders(Request $request){
        $query = Order::with('orderItems.product', 'transaction')
                    ->where('user_id', Auth::id());

        // --- Search by Order ID ---
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('id', 'like', "%{$search}%");
        }

        // --- Filter by order status ---
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // --- Filter by payment mode (from related transaction) ---
        if ($request->filled('payment_mode')) {
            $query->whereHas('transaction', function($q) use ($request) {
                $q->where('mode', $request->payment_mode);
            });
        }

        // --- Get paginated results ---
        $orders = $query->orderBy('created_at', 'desc')
                        ->paginate(5)
                        ->withQueryString();

        return view('user.orders', compact('orders'));
    } 
    public function order_details($order_id){

        $bankInfo = BankInfo::first();
         view()->share('bankInfo', $bankInfo);
        
        $order = Order::where('user_id',Auth::user()->id)->where('id',$order_id)->first();
        
        if($order){
            $orderItems = OrderItem::where('order_id',$order->id)->orderBy('id')->paginate(12);
            $transaction = Transaction::where('order_id',$order->id)->first();
            return view('user.order-details',compact('order','orderItems','transaction'));
        }else{
            return redirect()->route('login');
        }
    }
    public function order_cancel(Request $request){
        $order = Order::find($request->order_id);
        $order->status = "canceled";
        $order->canceled_date = Carbon::now();
        $order->save();
        return back()->with("status","Order has been canceled");
    }
    //email notification
    public function toggle(Request $request)
    {
        $user = Auth::user();

        // Check if notification record exists; if not, create one with default OFF
        $notification = $user->notification;

        if ($notification) {
            // Toggle the existing value
            $notification->update([
                'receive_updates' => !$notification->receive_updates,
            ]);
        } else {
            // Create a new record and turn ON notifications by default
            ProductNotification::create([
                'user_id' => $user->id,
                'receive_updates' => true,
            ]);
        }

        return back()->with('success', 'Your notification preference has been updated.');
    } 
    //address
    public function address(){
        $addresses = Address::where('user_id', Auth::id())->get();
        return view('user.address', compact('addresses'));
    }
    public function address_store(Request $request){
        $user_id = Auth::user()->id;
        $address = Address::where('user_id',$user_id)->where('isdefault',true)->first();
        if(!$address){
            $request->validate([
                'name' => 'required|max:100',
                'phone' => 'required|numeric|digits:10',
                'zip' => 'required|numeric|max:10',
                'state' => 'required',
                'city' => 'required',
                'address' => 'required',
                'locality' => 'required',
                'landmark' => 'required',
            ]);

            $address = new Address();
            $address->name = $request->name;
            $address->phone = $request->phone;
            $address->zip = $request->zip;
            $address->state = $request->state;
            $address->city = $request->city;
            $address->address = $request->address;
            $address->locality = $request->locality;
            $address->landmark = $request->landmark;
            $address->country = 'Korea';
            $address->user_id = $user_id;
            $address->isdefault = true;
            $address->save();
        }
        return redirect()->route('user.address')->with('status', 'Address has been added successfully!');
    }
    public function address_edit(){
        // Find the user's default address to pre-fill the form
        $address = Address::where('user_id', Auth::id())->where('isdefault', 1)->first();
        return view('user.address-edit', compact('address'));
    } 
    public function address_update(Request $request){
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'locality' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip' => 'required|string|max:20',
            'landmark' => 'nullable|string|max:255',
        ]);

        // Add user_id and default status to the data
        $validatedData['user_id'] = Auth::id();
        $validatedData['isdefault'] = true;
        $validatedData['country'] = 'Korea'; // Assuming a default country

        // Update the existing default address or create a new one
        Address::updateOrCreate(
            ['user_id' => Auth::id(), 'isdefault' => true],
            $validatedData
        );

        return redirect()->route('user.address')->with('success', 'Address saved successfully!');
    }
    //print
    public function print($order_id){
        $order = Order::find($order_id);
        $pdf = Pdf::loadView('user.invoice', compact('order'));
        return $pdf->download('invoice.pdf');
    }
    //reviews
    public function store_review(Request $request){
        $user_id = Auth::id();

        // Check if the user has a delivered order for the product
        $hasDeliveredOrder = OrderItem::whereHas('order', function ($query) use ($user_id) {
            $query->where('user_id', $user_id)
                ->where('status', 'delivered'); // Check for 'delivered' status
        })->where('product_id', $request->product_id)->exists();

        if (!$hasDeliveredOrder) {
            return redirect()->back()->with('error', 'You can only review products after they have been delivered.');
        }
        
        $reviews = Review::where('user_id', $user_id)
                            ->where('product_id', $request->product_id)
                            ->first();
        
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'image' => 'nullable|image|mimes:png,jpg,jpeg|max:40960',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string'
        ]);

        if (!$reviews) {
            $reviews = new Review();
            $reviews->user_id = $user_id;
            $reviews->product_id = $request->product_id;
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $file_extension = $image->extension();
            $file_name = Carbon::now()->timestamp.'.'.$file_extension;
            $this->GenerateReviewThumbnailsImage($image, $file_name);
            $reviews->image = $file_name;
        }

        $reviews->rating = $request->rating;
        $reviews->comment = $request->comment;
        $reviews->save();

        return redirect()->route('shop.product.details', ['product_slug' => $reviews->product->slug])
                        ->with('success', 'Review updated successfully!');
    }
    public function edit(Review $review){
        if ($review->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.'); 
        }
        $review->load('product');
        return view('user.update-review', compact('review'));
    }
    public function update_review(Request $request, Review $review){    
        if ($review->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.'); 
        }
        $user_id = Auth::id();
        $reviews = Review::where('user_id', $user_id)
                            ->where('product_id', $request->product_id)
                            ->first();
        
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'image' => 'nullable|image|mimes:png,jpg,jpeg|max:40960',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string'
        ]);
        if (!$reviews) {
                $reviews = new Review();
                $reviews->user_id = $user_id;
                $reviews->product_id = $request->product_id;
            }
        if($request->hasFile('image')){
                if(File::exists(public_path('uploads/reviews').'/'.$reviews->image)){
                    File::delete(public_path('uploads/reviews').'/'.$reviews->image);
                }
                $image = $request->file('image');
                $file_extention = $request-> file('image')->extension();
                $file_name = Carbon::now()->timestamp.'.'.$file_extention;
                $this -> GenerateReviewThumbnailsImage($image, $file_name);
                $reviews -> image = $file_name;
            }
        $reviews->rating = $request->rating;
        $reviews->comment = $request->comment;
        $reviews->save();

        return redirect()->route('shop.product.details', ['product_slug' => $review->product->slug])
                            ->with('success', 'Review updated successfully!');
    }
    public function delete_review(Review $review){
        $user = Auth::user();

        // 1. Check if the user is the review owner
        $is_owner = ($review->user_id === $user->id);

        // 2. Check if the user is an administrator based on 'utype'
        $is_admin = ($user->utype === 'ADM'); 

        // Abort if the user is neither the owner nor an admin
        if (!$is_owner && !$is_admin) {
            abort(403, 'Unauthorized action. You must be the review owner or an administrator to delete this review.');
        }

        // Proceed with file deletion and database record deletion
        if ($review->image) {
            $imagePath = public_path('uploads/reviews') . '/' . $review->image;
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        $review->delete();

        return redirect()->back()->with('success', 'Review deleted successfully!');
    }
    public function GenerateReviewThumbnailsImage($image, $imageName){
        $destination = public_path('uploads/reviews');
        $img = Image::read($image->path());
        $img -> cover(124,124,"top");
        $img -> resize(124,124,function($constraint){
            $constraint->aspectRatio();
        })->save($destination.'/'.$imageName);
    }   
    public function like(Review $review){
        
        // Creates the like if it doesn't already exist (enforced by unique database constraint)
        ReviewLike::firstOrCreate([
            'user_id' => Auth::id(),
            'review_id' => $review->id,
        ]);

        return back()->with('success', 'Review liked successfully!');
    } 
    public function unlike(Review $review){
        // Deletes the like for the current user and review
        ReviewLike::where('user_id', Auth::id())
                  ->where('review_id', $review->id)
                  ->delete();

        return back()->with('success', 'Review unliked successfully!');
    }
}
