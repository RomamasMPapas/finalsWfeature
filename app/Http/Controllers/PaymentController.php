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
            $metadata = json_decode($request->input('metadata'), true);
            
            $order = new Order;
            $order->first_name = $request->input('first_name');
            $order->last_name = $request->input('last_name');
            $order->status = 'pending';
            $order->address = $request->input('address');
            $order->user_id = $userId;
            $order->product_id = $metadata['product_id'];
            $order->payment_status = $paymentStatus;
            $order->delivery_status = "in progress";
            $order->payment_method = $paymentMethod;
            $order->amount = $request->input('amount');
            
            if($order->save()){
                // Delete from cart
                Cart::where('product_id', $metadata['product_id'])->where('user_id', $userId)->delete();
                
                // Update product quantity
                $product = Product::find($metadata['product_id']);
                if($product) {
                    $product->decrement('quantity', $metadata['quantity']);
                    $product->save();
                }
                
                return redirect('/')->with('payment_success', 'Order placed successfully! Payment method: ' . ucfirst($paymentMethod));
            }
            
            return redirect()->back()->with('error', 'Failed to process order. Please try again.');
            
        } catch(\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
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