<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Refund;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use Surfsidemedia\Shoppingcart\Facades\Cart;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CartController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('name')->get(); 
        $this->setAmountforCheckout();
        $items = Cart::instance('cart')->content();

        // Check if any product in the cart is out of stock
        $hasOutOfStock = false;

        foreach ($items as $item) {
            $product = $item->model;
            if ($product->quantity <= 0 || $product->stock_status === 'outstock') {
                $hasOutOfStock = true;
                break;
            }
        }

        return view('cart', compact('items', 'categories', 'hasOutOfStock'));
    }
    public function add_to_cart(Request $request){
        $product = Product::findOrFail($request->id);
        if ($product->quantity < $request->quantity) {
            return redirect()->back()->with('error', 'The requested quantity is not available.');
        }
        Cart::instance('cart')->add($request->id,$request->name,$request->quantity,$request->price)->associate('App\Models\Product');
        
        if (Auth::check()) {
            Cart::instance('cart')->store(Auth::id());
        }
        
        $this->setAmountforCheckout(); // Update total after adding item
        return redirect()->back();
    }
    public function increase_cart_quantity($rowId){
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty + 1;
        Cart::instance('cart')->update($rowId,$qty);

        if (Auth::check()) {
            Cart::instance('cart')->store(Auth::id());
        }

        $this->setAmountforCheckout(); // Update total after quantity change
        return redirect()->back();
    }
    public function decrease_cart_quantity($rowId){
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty - 1;
        Cart::instance('cart')->update($rowId,$qty);

        if (Auth::check()) {
            Cart::instance('cart')->store(Auth::id());
        }

        $this->setAmountforCheckout(); // Update total after quantity change
        return redirect()->back();
    }
     public function updateQty(Request $request, $rowId){
        // 1. Get the new quantity from the request
        if ($request->action) {
            // Logic for +/- buttons submitted via the form
            $item = Cart::instance('cart')->get($rowId);
            if ($request->action === 'increase') {
                $newQty = $item->qty + 1;
            } elseif ($request->action === 'decrease') {
                $newQty = max(1, $item->qty - 1); // Ensure qty is at least 1
            } else {
                $newQty = (int)$request->qty;
            }
        } else {
            // Logic for direct input or mass "Update Cart" button submit
            $newQty = (int)$request->qty;
        }

        // 2. Validate the quantity (must be at least 1)
        if ($newQty < 1) {
             $newQty = 1; 
        }

        try {
            // 3. Update the cart item
            Cart::instance('cart')->update($rowId, $newQty);

            // 4. Update session amounts (including delivery fee)
            $this->setAmountforCheckout();
            
            // 5. Store cart state if logged in
            if (Auth::check()) {
                Cart::instance('cart')->store(Auth::id());
            }

            // 6. Redirect back to the cart page
            return redirect()->route('cart.index')->with('success', 'Cart item quantity updated successfully!');
            
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            return redirect()->route('cart.index')->with('error', 'Could not update cart quantity.');
        }
    } 
    public function remove_item($rowId){
        Cart::instance('cart')->remove($rowId);

        if (Auth::check()) {
            Cart::instance('cart')->store(Auth::id());
        }
        $this->setAmountforCheckout();

        return redirect()->back();
    }
    public function empty_cart(){
        Cart::instance('cart')->destroy();

        if (Auth::check()) {
            Cart::instance('cart')->store(Auth::id());
        }
        Session::forget('checkout');
        Session::forget('coupon');
        Session::forget('discounts');
        return redirect()->back();
    }  
    //coupon
    public function apply_coupon_code(Request $request){
        $coupon_code = $request->coupon_code;
        if(isset($coupon_code)){
            // Convert cart subtotal from a formatted string (e.g., "50,000.00") to a number.
            $raw_subtotal = Cart::instance('cart')->subtotal();
            $numeric_subtotal = floatval(str_replace(',', '', $raw_subtotal));

            $coupon = Coupon::where('code',$coupon_code)
                ->where('expiry_date','>=',Carbon::today())
                ->where('cart_value','<=', $numeric_subtotal) // Use the numeric value for comparison.
                ->first();

            if(!$coupon){
                return redirect()->back()->with('error','Invalid coupon code');
            }else{
                Session::put('coupon',[
                    'code' => $coupon->code,
                    'type' => $coupon->type,
                    'value' => $coupon->value,
                    'cart_value' => $coupon->cart_value
                ]);
                $this->calculateDiscount();
                $this->setAmountforCheckout(); // Recalculate total with delivery
                return redirect()->back()->with('success','Coupon has been applied!');
            }
        }else{
            return redirect()->back()->with('error','Invalid coupon code');
        }
    }
    public function calculateDiscount()
    {
        $discount = 0;

        if (Session::has('coupon')) {
            // Normalize subtotal before calculation (in case Cart::subtotal() has commas)
            $rawSubtotal = Cart::instance('cart')->subtotal();
            $numericSubtotal = floatval(str_replace(',', '', $rawSubtotal));

            if (Session::get('coupon')['type'] == 'fixed') {
                $discount = Session::get('coupon')['value'];
            } else {
                $discount = ($numericSubtotal * Session::get('coupon')['value']) / 100;
            }

            $subtotalAfterDiscount = $numericSubtotal - $discount;
            $totalAfterDiscount = $subtotalAfterDiscount;

            // Store normalized values (no thousands separator)
            Session::put('discounts', [
                'discount' => number_format(floatval($discount), 2, '.', ''),
                'subtotal' => number_format(floatval($subtotalAfterDiscount), 2, '.', ''),
                'total'    => number_format(floatval($totalAfterDiscount), 2, '.', ''),
            ]);
        }
    } 
    public function remove_coupon_code(){
        Session::forget('coupon');
        Session::forget('discounts');
        $this->setAmountforCheckout(); // Recalculate total with delivery
        return redirect()->back()->with('success','Coupon has been removed!');
    }
    
    //Delivery Fee Calculation
    protected function getDeliveryFee(){
        $deliveryFee = 0;
        $totalQty = Cart::instance('cart')->content()->sum('qty');

        // 🔹 Fetch delivery settings from database
        $delivery = Delivery::first();

        // 🔹 Provide safe default values if record doesn’t exist yet
        $baseDeliveryFee = $delivery->delivery_fee ?? 4000;
        $minimumOrder = $delivery->minimum_order_amount ?? 50000;

        // 🔹 Get subtotal after discount (normalize commas)
        if (Session::has('discounts')) {
            $subtotalAfterDiscount = floatval(Session::get('discounts')['subtotal']);
        } else {
            $rawSubtotal = Cart::instance('cart')->subtotal();
            $subtotalAfterDiscount = floatval(str_replace(',', '', $rawSubtotal));
        }

        // 🔹 Logic for free delivery
        if ($subtotalAfterDiscount >= $minimumOrder || $totalQty >= 10) {
            $deliveryFee = 0;
        } else {
            $deliveryFee = $baseDeliveryFee;
        }

        return $deliveryFee;
    }
    public function setAmountforCheckout(){
        if(!Cart::instance('cart')->content()->count() > 0){
            Session::forget('checkout');
            return;
        }

        if(Session::has('coupon')){
            // Values are already formatted as "1234.56" (no thousands sep)
            $discount = Session::get('discounts')['discount'];
            $subtotal_after_discount = Session::get('discounts')['subtotal'];
            $total_after_discount = Session::get('discounts')['total']; // This is Subtotal - Discount
        }else{
            // Cart::subtotal() may contain commas like "9,000.00" — normalize
            $raw = Cart::instance('cart')->subtotal();
            $normalized = number_format(floatval(str_replace(',', '', $raw)), 2, '.', '');
            $discount = 0;
            $subtotal_after_discount = $normalized;
            $total_after_discount = $normalized;
        }

        $deliveryFee = $this->getDeliveryFee(); // Get the calculated fee
        
        // Calculate final total including delivery fee
        $finalTotal = floatval($total_after_discount) + $deliveryFee;
        $finalTotalFormatted = number_format($finalTotal, 2, '.', '');

        // store normalized values (no thousands separator) in session
        Session::put('checkout',[
            'discount' => (string) number_format(floatval($discount), 2, '.', ''),
            'subtotal' => (string) number_format(floatval($subtotal_after_discount), 2, '.', ''), // Subtotal after coupon/before delivery
            'delivery_fee' => (string) number_format($deliveryFee, 2, '.', ''), // New field
            'total' => (string) $finalTotalFormatted, // Final total after all additions/deductions
        ]);
    }
    //checkout
    public function checkout(){
        $items = Cart::instance('cart')->content();
        $categories = Category::orderBy('name')->get();
        if(!Auth::check()){
            return redirect()->route('login');
        }
        $this->setAmountforCheckout(); // Ensure delivery fee is calculated
        $address = Address::where('user_id',Auth::user()->id)->where('isdefault',1)->first();
        return view('checkout',compact('address','categories','items'));
    }
    //place order
    public function place_an_order(Request $request){
        $user_id = Auth::user()->id;

        // Validate address fields submitted from the form
        $request->validate([
            'name' => 'required|max:100',
            'phone' => 'required|numeric|digits:10',
            'zip' => 'required|numeric|digits:4',
            'state' => 'required',
            'city' => 'required',
            'address' => 'required',
            'locality' => 'required',
            'landmark' => 'nullable',
        ]);

        // Use updateOrCreate to handle both creating a new address and updating an existing one.
        // This finds the user's default address and updates it with the form data, or creates it if it doesn't exist.
        $address = Address::updateOrCreate(
            ['user_id' => $user_id, 'isdefault' => true],
            [
                'name' => $request->name,
                'phone' => $request->phone,
                'zip' => $request->zip,
                'state' => $request->state,
                'city' => $request->city,
                'address' => $request->address,
                'locality' => $request->locality,
                'landmark' => $request->landmark,
                'country' => 'Korea',
            ]
        ); 
        //payment
        if($request->mode == 'card'){
            $this->setAmountforCheckout();
            // Save all necessary checkout info to session
            Session::put('stripe_checkout', [
                'address' => [
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'zip' => $request->zip,
                    'state' => $request->state,
                    'city' => $request->city,
                    'address' => $request->address,
                    'locality' => $request->locality,
                    'landmark' => $request->landmark,
                    'country' => 'Korea',
                ],
                'checkout' => Session::get('checkout', []),
            ]);

            return redirect()->route('cart.payment.card', [
                'amount' => Session::get('checkout.total'),
            ]);
        }elseif($request->mode == "paypal"){
            $this->setAmountforCheckout();

            $order = new Order();
            $order->user_id = $user_id;
            
            $checkout = Session::get('checkout', []);
            $rawSubtotal = $checkout['subtotal'] ?? ($checkout['total'] ?? 0);
            $rawDiscount = $checkout['discount'] ?? 0;
            $rawDeliveryFee = $checkout['delivery_fee'] ?? 0; // NEW: Get Delivery Fee
            $rawTotal = $checkout['total'] ?? $rawSubtotal;

            // remove thousands separator and cast to float
            $order->subtotal = floatval(str_replace(',', '', $rawSubtotal));
            $order->discount = floatval(str_replace(',', '', $rawDiscount));
            $order->tax = 0;
            $order->delivery_fee = floatval(str_replace(',', '', $rawDeliveryFee)); // NEW: Set Delivery Fee
            $order->total = floatval(str_replace(',', '', $rawTotal));
            $order->name = $address->name;
            $order->phone = $address->phone;
            $order->locality = $address->locality;
            $order->address = $address->address;
            $order->city = $address->city;
            $order->state = $address->state;
            $order->country = $address->country;
            $order->landmark = $address->landmark;
            $order->zip = $address->zip;
            $order->save();
            // --- END Order creation with Delivery Fee ---
        
            // add order items
            foreach (Cart::instance('cart')->content() as $item) {
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $item->id;
                $orderItem->price = $item->price;
                $orderItem->quantity = $item->qty;
                $orderItem->save();
            }
            $this->reduceProductInventory(); 
            
                $transaction = new Transaction();
                $transaction->user_id = $user_id;
                $transaction->order_id = $order->id;
                $transaction->mode = $request->mode;
                $transaction->status = "pending";
                $transaction->save();
                Cart::instance('cart')->destroy();
                Session::forget('checkout');
                Session::forget('coupon');
                Session::forget('discounts');
                Session::put('order_id',$order->id);
                return redirect()->route('cart.order.confirmation');
            
        }elseif($request->mode == "cod"){
            $this->setAmountforCheckout();

            $order = new Order();
            $order->user_id = $user_id;
            
            $checkout = Session::get('checkout', []);
            $rawSubtotal = $checkout['subtotal'] ?? ($checkout['total'] ?? 0);
            $rawDiscount = $checkout['discount'] ?? 0;
            $rawDeliveryFee = $checkout['delivery_fee'] ?? 0; // NEW: Get Delivery Fee
            $rawTotal = $checkout['total'] ?? $rawSubtotal;

            // remove thousands separator and cast to float
            $order->subtotal = floatval(str_replace(',', '', $rawSubtotal));
            $order->discount = floatval(str_replace(',', '', $rawDiscount));
            $order->tax = 0;
            $order->delivery_fee = floatval(str_replace(',', '', $rawDeliveryFee)); // NEW: Set Delivery Fee
            $order->total = floatval(str_replace(',', '', $rawTotal));
            $order->name = $address->name;
            $order->phone = $address->phone;
            $order->locality = $address->locality;
            $order->address = $address->address;
            $order->city = $address->city;
            $order->state = $address->state;
            $order->country = $address->country;
            $order->landmark = $address->landmark;
            $order->zip = $address->zip;
            $order->save();
            // --- END Order creation with Delivery Fee ---
        
        // add order items
        foreach (Cart::instance('cart')->content() as $item) {
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $item->id;
            $orderItem->price = $item->price;
            $orderItem->quantity = $item->qty;
            $orderItem->save();
        }
        $this->reduceProductInventory(); 
        
            $transaction = new Transaction();
            $transaction->user_id = $user_id;
            $transaction->order_id = $order->id;
            $transaction->mode = $request->mode;
            $transaction->status = "pending";
            $transaction->save();
            Cart::instance('cart')->destroy();
            Session::forget('checkout');
            Session::forget('coupon');
            Session::forget('discounts');
            Session::put('order_id',$order->id);
            return redirect()->route('cart.order.confirmation');
        }
    } 
    //Stripe
    public function renderCardPayment()
    {
        $checkout = Session::get('stripe_checkout');
        if (!$checkout) {
            return redirect()->route('cart.index')->withErrors('Payment session expired.');
        }

        $amount = $checkout['checkout']['total'] ?? 0;
        $categories = Category::orderBy('name')->get();

        return view('payment', compact('amount', 'categories'));
    }
    public function stripeCheckout(Request $request)
    {
        $user_id = Auth::id();
        $sessionData = Session::get('stripe_checkout'); // FIXED key

        if (!$sessionData) {
            return redirect()->route('cart.index')->withErrors('Payment session expired.');
        }

        $address = $sessionData['address'];
        $checkout = $sessionData['checkout'];

        // 1️⃣ Create the order
        $order = Order::create([
            'user_id' => $user_id,
            'subtotal' => floatval(str_replace(',', '', $checkout['subtotal'] ?? 0)),
            'discount' => floatval(str_replace(',', '', $checkout['discount'] ?? 0)),
            'tax' => 0,
            'delivery_fee' => floatval(str_replace(',', '', $checkout['delivery_fee'] ?? 0)),
            'total' => floatval(str_replace(',', '', $checkout['total'] ?? 0)),
            'name' => $address['name'],
            'phone' => $address['phone'],
            'locality' => $address['locality'],
            'address' => $address['address'],
            'city' => $address['city'],
            'state' => $address['state'],
            'country' => $address['country'],
            'landmark' => $address['landmark'],
            'zip' => $address['zip'],
        ]);

        // 2️⃣ Add order items
        foreach (Cart::instance('cart')->content() as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->id,
                'price' => $item->price,
                'quantity' => $item->qty,
            ]);
        }

        $this->reduceProductInventory();

        // 3️⃣ Charge via Stripe
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
        $amount = intval(str_replace(',', '', $checkout['total'] ?? 0));

        $charge = $stripe->charges->create([
            'amount' => $amount,
            'currency' => 'krw',
            'source' => $request->stripeToken,
            'description' => 'Payment for Order #' . $order->id,
        ]); 
        // 4️⃣ Save transaction
        Transaction::create([
            'user_id' => $user_id,
            'order_id' => $order->id,
            'mode' => 'card',
            'status' => 'approved',
            'transaction_id' => $charge->id,
        ]);

        // 5️⃣ Clear cart & session
        Cart::instance('cart')->destroy();
        Session::forget(['checkout', 'coupon', 'discounts', 'stripe_checkout']);
        Session::put('order_id', $order->id);

        return redirect()->route('cart.order.confirmation');
    }
    public function refund($order_id)
    {
        // 1️⃣ Find the order and its transaction
        $order = Order::with('orderItems.product', 'transaction')->findOrFail($order_id);
        $transaction = $order->transaction;

        if (!$transaction || !$transaction->transaction_id) {
            return back()->withErrors('No valid transaction found for this order.');
        }

        try {
            // 2️⃣ Set the Stripe API key
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

            // 3️⃣ Create a refund
            $refund = \Stripe\Refund::create([
                'charge' => $transaction->transaction_id, // Stripe charge ID
                // 'amount' => 5000, // optional: refund partial amount (in cents)
                'reason' => 'requested_by_customer',
            ]);

            // 4️⃣ Update transaction status locally
            $transaction->update([
                'status' => 'refunded',
            ]);

            // 5️⃣ Update order status
            $order->update([
                'status' => 'refunded',
            ]);

            // 6️⃣ Restore product stock (if products exist)
            foreach ($order->orderItems as $item) {
                if ($item->product) {
                    $item->product->increment('quantity', $item->quantity);
                }
            }

            return back()->with('success', 'Refund processed successfully and product stock restored!');
        } catch (\Exception $e) {
            return back()->withErrors('Refund failed: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, $id)
{
    $order = Order::with('orderItems.product')->findOrFail($id);
    $newStatus = $request->input('status');

    // Update order status
    $order->transaction->status = $newStatus;
    $order->save();

    // If marking as refunded, restore stock
    if ($newStatus === 'refunded') {
        foreach ($order->orderItems as $item) {
            if ($item->product) {
                $item->product->increment('quantity', $item->quantity);
            }
        }

        // Update transaction status if exists
        if ($order->transaction) {
            $order->transaction->update(['status' => 'refunded']);
        }
    }

    return back()->with('success', "Order status updated to {$newStatus}.");
}

    public function order_confirmation(){
        $bankInfo = \App\Models\BankInfo::first();

        if(Session::has('order_id')) {
            $order = Order::find(Session::get('order_id'));
            
            if ($order) {
                // load latest transaction if exists
                $txn = Transaction::where('order_id', $order->id)->orderByDesc('id')->first();
                // derive human-friendly payment status
                $paymentStatus = $this->paymentStatusForOrder($order);

                // pass order, txn and paymentStatus to the view
                return view('order-confirmation', compact('order','txn','paymentStatus','bankInfo'));
            }
        }
        return redirect()->route('cart.index');
    }   
    private function reduceProductInventory(){
        // Loop through the cart content
        foreach (Cart::instance('cart')->content() as $item) {
            // Find the corresponding Product model
            $product = Product::find($item->id);

            if ($product) {
                // Subtract the quantity purchased (item->qty) from the product's stock
                // Ensure you don't go below zero if possible, though validation should handle this.
                $new_quantity = $product->quantity - $item->qty;
                
                // Laravel's decrement is safer for database operations
                $product->decrement('quantity', $item->qty);

                // Optional: Mark product as out of stock if quantity reaches 0
                if ($new_quantity <= 0) {
                    // Assuming your product model has a stock_status column
                    // $product->stock_status = 'outofstock';
                    // $product->save();
                }
            }
        }
    }
    protected function paymentStatusForOrder(Order $order){
        $txn = Transaction::where('order_id', $order->id)->first();
        if (!$txn) {
            return 'unpaid';
        }
        // FIX: Check against the string 'approved'
        return ($txn->status === 'approved') ? 'approved' : 'pending';
    }
    public function uploadReceipt(Request $request, $id)
    {
        $request->validate([
            'receipt_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // max 5MB
        ]);

        $order = Order::findOrFail($id);
        $transaction = $order->transaction;
        if ($request->hasFile('receipt_image')) {
        // 🧹 Delete old file if it exists
        if (File::exists(public_path('uploads/receipts/' . $order->receipt_image))) {
            File::delete(public_path('uploads/receipts/' . $order->receipt_image));
        }

        // 📸 Handle new file
        $file = $request->file('receipt_image');
        $extension = $file->getClientOriginalExtension();
        $timestamp = now()->timestamp;
        $fileName = 'receipt-' . $order->id . '-' . $timestamp . '.' . $extension;

        // 📂 Move file to the receipts directory
        $destination = public_path('uploads/receipts');
        if (!File::exists($destination)) {
            File::makeDirectory($destination, 0755, true);
        }

        $file->move($destination, $fileName);

        // 💾 Save filename to DB
        $order->receipt_image = $fileName;
        $order->save();
        if ($transaction) {
                $transaction->status = 'approved';
                $transaction->save();
            }
        }

        return back()->with('success', 'Receipt uploaded successfully!');
    }
}
