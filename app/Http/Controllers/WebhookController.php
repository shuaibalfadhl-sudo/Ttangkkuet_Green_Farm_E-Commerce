<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use App\Models\Transaction; // Make sure to use your Transaction model
use App\Models\Order; // If needed to link to the order
use Surfsidemedia\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Session;

class WebhookController extends Controller
{
    /**
     * Handle Stripe webhook events.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handleWebhook(Request $request)
    {
        // Set your Stripe API key
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        // // Retrieve the event payload from the request
        // $payload = @file_get_contents('php://input');
        // $event = null;

        // try {
        //     // Construct the event object
        //     $event = \Stripe\Event::constructFrom(
        //         json_decode($payload, true)
        //     );
        // } catch (\UnexpectedValueException $e) {
        //     // Invalid payload, return a 400 response
        //     return response('', 400);
        // }

        // // Handle the event
        // switch ($event->type) {
        //     case 'checkout.session.completed':
        //         $session = $event->data->object;
                
        //         // Get the order ID from the session metadata
        //         $order_id = $session->metadata->order_id;
        //         $user_id = $session->metadata->user_id;

        //         // Find the existing transaction (if you created a "pending" one)
        //         // or create a new one based on the order ID
        //         $transaction = Transaction::where('order_id', $order_id)->first();
                
        //         if ($transaction) {
        //             // Update an existing "pending" transaction
        //             $transaction->status = 'completed';
        //             $transaction->payment_id = $session->payment_intent;
        //             $transaction->mode = 'card';
        //             $transaction->save();
        //         } else {
        //             // Create a new transaction if it doesn't exist
        //             $newTransaction = new Transaction();
        //             $newTransaction->user_id = $user_id;
        //             $newTransaction->order_id = $order_id;
        //             $newTransaction->mode = 'card';
        //             $newTransaction->status = 'completed';
        //             $newTransaction->payment_id = $session->payment_intent;
        //             $newTransaction->save();
        //         }

        //         // Clear the cart and sessions
        //         Cart::instance('cart')->destroy();
        //         Session::forget('checkout');
        //         Session::forget('coupon');
        //         Session::forget('discounts');
        //         Session::forget('order_id');

        //         break;
        //     default:
        //         // Handle other event types
        //         // For example, if you want to handle failures, refunds, etc.
        //         break;
        // }

        // // Acknowledge the event
        // return response('', 200);
    }
}