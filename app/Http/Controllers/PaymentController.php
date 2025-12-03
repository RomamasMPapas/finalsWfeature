<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use App\Models\Order; 
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{

    /**
     * Redirect the User to Payment Gateway based on selected method
     * @return Url
     */
    public function redirectToGateway(Request $request)
    {
        $paymentMethod = $request->input('payment_method', 'cod');
        
        // For now, we'll process all payments as "pending" until we integrate the actual gateways
        switch($paymentMethod) {
            case 'paypal':
                // TODO: Integrate PayPal SDK
                return $this->processOrder($request, 'paypal', 'pending');
                
            case 'card':
                // TODO: Integrate Stripe SDK
                return $this->processOrder($request, 'card', 'pending');
                
            case 'cod':
            default:
                return $this->processOrder($request, 'cash_on_delivery', 'pending');
        }
    }

    /**
     * Process order with selected payment method
     */
    private function processOrder(Request $request, $paymentMethod, $paymentStatus)
    {
        try {
            $userId = Auth::id();
            
            if(!$userId) {
                return redirect('/login')->with('error', 'Please login to place an order');
            }
            
            $cartItems = Cart::where('user_id', $userId)->get();
            
            if($cartItems->isEmpty()) {
                return redirect('/')->with('error', 'Your cart is empty');
            }

            $otp = rand(100000, 999999);
            
            // Generate a random delivery date between 3 to 7 days from now
            $deliveryDate = \Carbon\Carbon::now()->addDays(rand(3, 7))->format('Y-m-d');

            $orderCount = 0;
            foreach($cartItems as $item) {
                $order = new Order;
                
                // Get user data from request
                $order->first_name = $request->input('first_name', '');
                $order->last_name = $request->input('last_name', '');
                $order->status = 'pending';
                $order->address = $request->input('address', '');
                $order->user_id = $userId;
                $order->product_id = $item->product_id;
                $order->payment_status = $paymentStatus;
                $order->delivery_status = "in progress";
                $order->payment_method = $paymentMethod;
                $order->otp = $otp;
                $order->delivery_date = $deliveryDate;
                
                // Fetch product price to be accurate
                $product = Product::find($item->product_id);
                $order->amount = $product ? $product->price : 0;

                if($order->save()) {
                    $orderCount++;
                    
                    // Update product quantity
                    if($product && $product->quantity > 0) {
                        $product->decrement('quantity', 1); 
                    }
                }
            }
            
            // Clear cart only if orders were created successfully
            if($orderCount > 0) {
                Cart::where('user_id', $userId)->delete();
                
                return redirect('/delivery')->with('payment_success', 'Order placed successfully! ' . $orderCount . ' item(s) ordered. Payment method: ' . ucfirst($paymentMethod) . '. Your OTP: ' . $otp);
            } else {
                return redirect()->back()->with('error', 'Failed to create orders. Please try again.');
            }
            
        } catch(\Exception $e) {
            \Log::error('Order processing error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while processing your order: ' . $e->getMessage());
        }
    }

    /**
     * Handle payment callback (for future PayPal/Stripe integration)
     * @return void
     */
    public function handleGatewayCallback(Request $request)
    {
        // TODO: Implement callback handling for PayPal and Stripe
        return redirect('/')->with('info', 'Payment callback received');
    }
}