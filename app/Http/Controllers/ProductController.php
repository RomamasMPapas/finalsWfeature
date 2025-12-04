<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ArchivedProduct;
use App\Models\Cart;
use App\Models\User;
use App\Models\Order;
use App\Models\Products_category;
use Illuminate\Support\Carbon;
use Session;
use DB;
use Illuminate\Support\Facades\Auth;
class ProductController extends Controller
{
    // get all the products in the database
    function index(){
        $data = Product::inRandomOrder()->get();
        $popular_products = Product::inRandomOrder()->limit(4)->get();
        return view('home',['products'=>$data, 'popular_products'=>$popular_products]);
    }
    // display each product by its id
    function product($id){
        $product = Product::find($id);
        $related_products = Product::where('category', $product->category)->where('id', '!=', $id)->inRandomOrder()->limit(4)->get();
        return view('products',['product'=>$product, 'related_products'=>$related_products]);
    }

    // display all products
    function all_products(){
        // Get all products grouped by category
        $products = Product::orderBy('category', 'asc')->orderBy('name', 'asc')->get();
        
        // Group products by category
        $productsByCategory = $products->groupBy('category');
        
        // Get all categories for the filter
        $categories = Products_category::orderBy('category', 'asc')->get();
        
        return view('all-products', [
            'productsByCategory' => $productsByCategory,
            'categories' => $categories
        ]);
    }
    //add to cart functionality
    function cart(Request $req){
        if (Auth::check()) {
            $cart = new Cart;
            $cart->user_id = Auth::id();
            $cart->product_id = $req->id;
            $cart->save();
            return response()->json(['code'=>'1','msg'=>'success']);

        }
        else{
            return response()->json(['code'=>'0','msg'=>'error']);
        }
    }

    // count added items in the cart
    static function CartNum(){
        if (Auth::check()) {
            $user_id = Auth::id();
            return Cart::where('user_id',$user_id)->count();
        }   
        else{
            return redirect('/login');
        }
    }

    // display all the products in the cart
    function cartlist(){
        if (Auth::check()) {
            $user_id = Auth::id();
            $products = DB::table('carts')
            ->join('products','carts.product_id','=','products.id')
            ->where('carts.user_id',$user_id)
            ->select('products.*','carts.id as cart_id')
            ->get();
            // user data
            $user_data = User::find($user_id);
            return view("cartlist",compact('products','user_data'));
        }
        else{
            return redirect('/');
        }
    }
    
    // remove product from the cart
    function remove(Request $req){
        if (Auth::check()) {
            $id = $req->id;
            Cart::destroy($id);
            return response()->json(['code'=>$id,'msg'=>'success']);
        }
        else{
            return redirect('/');
        }
    }

    // search product from the database
    function search_products(Request $request){
        $search = $request->search;
        $product = Product::where('name','like','%'.$search.'%')->get();
        return  view('/search',['products'=> $product]);
    }

    // order now
    function checkout(){
        if (Auth::check()) {
            $user_id = Auth::id();
            // total products value
            $total = DB::table('carts')
            ->join('products','carts.product_id','=','products.id')
            ->where('carts.user_id',$user_id)
            ->select('products.*','carts.id as cart_id')
            ->sum('products.price');
            // total products ordered
            $products = DB::table('carts')
            ->join('products','carts.product_id','=','products.id')
            ->where('carts.user_id',$user_id)
            ->select('products.*','carts.id as cart_id')
            ->get();
            // user data
            $user_data = User::find($user_id);
            return view("checkout",compact('products','user_data','total'));
            // return view('checkout',['total'=>$total]);
        }
        else{
            return redirect('/login');
        }
    }

    // order placement
    function order_now(Request $req){
        if (Auth::check()) {
            $user_id = Auth::id();
            $all_cart = Cart::where('user_id',$user_id)->get();
            $otp = rand(100000, 999999);
            // Generate a random delivery date between 3 to 7 days from now
            $deliveryDate = \Carbon\Carbon::now()->addDays(rand(3, 7))->format('Y-m-d');

            foreach ($all_cart as $cart) {
                $order = new Order;
                $order->user_id = $cart['user_id'];
                $order->product_id = $cart['product_id'];
                $order->status = "pending";
                $order->payment_method = $req->payment;
                $order->payment_status = "pending";
                $order->address = $req->address;
                $order->otp = $otp;
                $order->delivery_date = $deliveryDate;
                $order->Save();
            }
            Cart::where('user_id',$user_id)->delete();
            return redirect('/delivery')->with('payment_success', 'Order placed successfully! Your OTP for offline use is: ' . $otp);
        }
        else{
            return redirect('/login');
        }
    }

    // display delivery page with user orders
    function delivery(){
        if (Auth::check()) {
            $user_id = Auth::id();
            $orders = DB::table('orders')
                ->join('products', 'orders.product_id', '=', 'products.id')
                ->where('orders.user_id', $user_id)
                ->where('orders.delivery_status', '!=', 'cancelled')
                ->select('products.*', 'orders.*', 'orders.id as order_id')
                ->orderBy('orders.created_at', 'desc')
                ->get();
            
            return view('delivery', compact('orders'));
        }
        else{
            return redirect('/login');
        }
    }

    // cancel order
    function cancel_order($id){
        if (Auth::check()) {
            $user_id = Auth::id();
            $order = Order::where('id', $id)->where('user_id', $user_id)->first();
            
            if($order && $order->delivery_status != 'delivered'){
                $order->delivery_status = 'cancelled';
                $order->save();
                return redirect('/delivery')->with('payment_success', 'Order cancelled successfully');
            } else {
                return redirect('/delivery')->with('error', 'Cannot cancel this order');
            }
        }
        else{
            return redirect('/login');
        }
    }

    // dashboard functionality

    // display every product
    function products_all(){
        $products = Product::orderBy('id', 'desc')->paginate(10);
        $form_categories = Products_category::all();
        $orders = DB::table('orders')->
        leftJoin('products','orders.product_id','=','products.id')->
        orderBy('orders.id','desc')->limit(3)->get();
        $count_orders = DB::table('orders')->count();
        $orders_inprogress = DB::table('orders')->where('delivery_status','in progress')->count();
        $orders_delivered = DB::table('orders')->where('delivery_status','delivered')->count();
        $orders_cancelled = DB::table('orders')->where('delivery_status','cancelled')->count();
        // records for chart - SQLite compatible
        $record = Order::select(
            \DB::raw("COUNT(*) as count"), 
            \DB::raw("strftime('%w', created_at) as day_name"),
            \DB::raw("strftime('%d', created_at) as day")
        )
        ->where('created_at', '>', Carbon::today()->subDay(60))
        ->groupBy('day_name','day')
        ->orderBy('day')
        ->get();
      
         $data = [];
     
         foreach($record as $row) {
            $data['label'][] = $row->day_name;
            $data['data'][] = (int) $row->count;
          }
     
          $chart_data = json_encode($data);
        return view('manage-products', compact('orders','chart_data','count_orders','orders_delivered','form_categories','orders_inprogress','orders_cancelled','products'));            
    }

    // display products by category for admin
    function products_by_category($category_name){
        if(Session::has('admin')){
            $products = Product::where('category', $category_name)->orderBy('id', 'desc')->paginate(10);
            $form_categories = Products_category::all();
            $orders = DB::table('orders')->
            leftJoin('products','orders.product_id','=','products.id')->
            orderBy('orders.id','desc')->limit(3)->get();
            $count_orders = DB::table('orders')->count();
            $orders_inprogress = DB::table('orders')->where('delivery_status','in progress')->count();
            $orders_delivered = DB::table('orders')->where('delivery_status','delivered')->count();
            $orders_cancelled = DB::table('orders')->where('delivery_status','cancelled')->count();
            // records for chart - SQLite compatible
            $record = Order::select(
                \DB::raw("COUNT(*) as count"), 
                \DB::raw("strftime('%w', created_at) as day_name"),
                \DB::raw("strftime('%d', created_at) as day")
            )
            ->where('created_at', '>', Carbon::today()->subDay(60))
            ->groupBy('day_name','day')
            ->orderBy('day')
            ->get();
        
            $data = [];
        
            foreach($record as $row) {
                $data['label'][] = $row->day_name;
                $data['data'][] = (int) $row->count;
            }
        
            $chart_data = json_encode($data);
            return view('manage-products', compact('orders','chart_data','count_orders','orders_delivered','form_categories','orders_inprogress','orders_cancelled','products'));
        }
        else {
            return redirect('/admin');
        }
    }

    // display archived products
    function archived_products(){
        $products = ArchivedProduct::orderBy('id', 'desc')->paginate(10);
        $form_categories = Products_category::all();
        $orders = DB::table('orders')->
        leftJoin('products','orders.product_id','=','products.id')->
        orderBy('orders.id','desc')->limit(3)->get();
        $count_orders = DB::table('orders')->count();
        $orders_inprogress = DB::table('orders')->where('delivery_status','in progress')->count();
        $orders_delivered = DB::table('orders')->where('delivery_status','delivered')->count();
        $orders_cancelled = DB::table('orders')->where('delivery_status','cancelled')->count();
        // records for chart - SQLite compatible
        $record = Order::select(
            \DB::raw("COUNT(*) as count"), 
            \DB::raw("strftime('%w', created_at) as day_name"),
            \DB::raw("strftime('%d', created_at) as day")
        )
        ->where('created_at', '>', Carbon::today()->subDay(60))
        ->groupBy('day_name','day')
        ->orderBy('day')
        ->get();
      
         $data = [];
     
         foreach($record as $row) {
            $data['label'][] = $row->day_name;
            $data['data'][] = (int) $row->count;
          }
     
          $chart_data = json_encode($data);
        return view('archived-products', compact('orders','chart_data','count_orders','orders_delivered','form_categories','orders_inprogress','orders_cancelled','products'));            
    }
    // add product
    function add_products(Request $req){
        if (Session()->has('admin')) {
        // validate the input
            $validate = $req->validate([
                'image'=>'required|image|max:2048|mimes:jpeg,jpg,png,gif,svg',
            ]);
            $imageName = time() . '.' . $req->image->extension();
            // store the image in the public folder
            if(!$req->image->move(public_path('assets/images'),$imageName)){ // if images is not valid
                return back()->with('error','error uploading image, check if it is image');
            }
            // new product id for the product
            $product_id = 'Id'.round(microtime(true)); 
            // instantiating the product class
            $product = new Product;
            $product->name = $req->name;
            $product->price = $req->price;
            $product->category = $req->category;
            $product->description = $req->description;
            $product->gallery = $imageName; // product image path
            $product->quantity = $req->quantity;
            $product->product_id = $product_id;
            if($product->save()){ // if product is added
                return back()->with('success','New product added successfully');
            }
            else{
                return back()->with('error','error adding product, try later! ');
            }    # code...

        }
        else {
            return redirect('/admin');
        }
        
    }
    
    // add category
    // add category
    function add_category(Request $request){
        // if(Session::has('admin')){
            $category = new Products_category;
            $category->category = ucfirst($request->name);
            if($category->save()){ // if product is added
                return response()->json(['code' =>'1','msg'=>'success']);
            }
            else{
                return response()->json(['code' =>'0','msg'=>'error']);
            }
        // }
        // else {
        //     return redirect('/admin');
        // }
       
    }
    function show_categories(){
        if(Session::has('admin')){
            $categories = Products_category::all();
            $form_categories = Products_category::all();
            $orders = DB::table('orders')->
            leftJoin('products','orders.product_id','=','products.id')->
            orderBy('orders.id','desc')->limit(3)->get();
            $count_orders = DB::table('orders')->count();
            $orders_inprogress = DB::table('orders')->where('delivery_status','in progress')->count();
            $orders_delivered = DB::table('orders')->where('delivery_status','delivered')->count();
            $orders_cancelled = DB::table('orders')->where('delivery_status','cancelled')->count();
             // records for chart - SQLite compatible
            $record = Order::select(
                \DB::raw("COUNT(*) as count"), 
                \DB::raw("strftime('%w', created_at) as day_name"),
                \DB::raw("strftime('%d', created_at) as day")
            )
            ->where('created_at', '>', Carbon::today()->subDay(60))
            ->groupBy('day_name','day')
            ->orderBy('day')
            ->get();
        
            $data = [];
        
            foreach($record as $row) {
                $data['label'][] = $row->day_name;
                $data['data'][] = (int) $row->count;
            }
        
            $chart_data = json_encode($data);
            return view('manage-categories', compact('orders','chart_data','form_categories','count_orders','orders_delivered','orders_inprogress','orders_cancelled','categories'));
            
        }
        else {
            return redirect('/admin');
        }
        // return view('manage-categories',['categories'=>$categories]);
    }
    function delete_cat(Request $req){
        $id = $req->cat_id;
        $query = Products_category::where('id',$id)->delete();
        if ($query) {
            # code...
            return response()->json(['code'=>0,'msg'=>'deleted']);
        }
        else {
            return response()->json(['code'=>0,'msg'=>'undeleted']);
        }
    }
    // show product for editing according to their id
    // show product for editing according to their id
    function show_product($id){
        if (Session()->has('admin')) {
            $product = Product::find($id);
            $form_categories = Products_category::all();
            $orders = DB::table('orders')->
            leftJoin('products','orders.product_id','=','products.id')->
            orderBy('orders.id','desc')->limit(3)->get();
            $count_orders = DB::table('orders')->count();
            $orders_inprogress = DB::table('orders')->where('delivery_status','in progress')->count();
            $orders_delivered = DB::table('orders')->where('delivery_status','delivered')->count();
            $orders_cancelled = DB::table('orders')->where('delivery_status','cancelled')->count();
            // records for chart - SQLite compatible
            $record = Order::select(
                \DB::raw("COUNT(*) as count"), 
                \DB::raw("strftime('%w', created_at) as day_name"),
                \DB::raw("strftime('%d', created_at) as day")
            )
            ->where('created_at', '>', Carbon::today()->subDay(60))
            ->groupBy('day_name','day')
            ->orderBy('day')
            ->get();
        
            $data = [];
        
            foreach($record as $row) {
                $data['label'][] = $row->day_name;
                $data['data'][] = (int) $row->count;
            }
        
            $chart_data = json_encode($data);
            return view('edit-product', compact('product','orders','chart_data','count_orders','orders_delivered','form_categories','orders_inprogress','orders_cancelled'));
        }
        else{
            return redirect('/admin');
        }
        
    }

    // update product functionalities
    function update_products(Request $req){
        if (Session()->has('admin')) {
           // validate the input
            $validate = $req->validate([
                'image'=>'required|image|max:2048|mimes:jpeg,jpg,png,gif,svg',
            ]);
            $imageName = time() . '.' . $req->image->extension();
            // store the image in the public folder
            if(!$req->image->move(public_path('assets/images'),$imageName)){ // if images is not valid
                return back()->with('error','error uploading image, check if it is image');
            }
            $id = $req->id; 
            // instantiating the product class
            $product = Product::find($id);
            $product->name = $req->name;
            $product->price = $req->price;
            $product->category = $req->category;
            $product->description = $req->description;
            $product->gallery = $imageName; // product image path
            $product->quantity = $req->quantity;
            $product->product_id = $req->product_id;
            if($product->save()){ // if product is added
                return back()->with('success','Product edited successfully');
            }
            else{
                return back()->with('error','error editing product, try later! ');
            }

        }
        else {
            return redirect('/admin');
        }
        
    }

    // delete product functionality
    function delete_product(Request $req){
        $id = $req->prd_id;
        $product = Product::find($id);
        if ($product) {
            // Archive the product before deletion
            $archived = new ArchivedProduct();
            $archived->product_id = $product->product_id;
            $archived->name = $product->name;
            $archived->price = $product->price;
            $archived->category = $product->category;
            $archived->description = $product->description;
            $archived->gallery = $product->gallery;
            $archived->quantity = $product->quantity;
            $archived->save();
            // Delete original product
            $product->delete();
            return response()->json(['code'=>0,'msg'=>'deleted and archived']);
        }
        else {
            return response()->json(['code'=>0,'msg'=>'product not found']);
        }
    }

    // Restore archived product back to products table
    function restore_product(Request $req){
        $archived_id = $req->archived_id;
        $archived = ArchivedProduct::find($archived_id);
        if ($archived) {
            $product = new Product();
            $product->product_id = $archived->product_id;
            $product->name = $archived->name;
            $product->price = $archived->price;
            $product->category = $archived->category;
            $product->description = $archived->description;
            $product->gallery = $archived->gallery;
            $product->quantity = $archived->quantity;
            $product->save();
            // Remove from archive
            $archived->delete();
            return response()->json(['code'=>1,'msg'=>'restored']);
        }
        else {
            return response()->json(['code'=>0,'msg'=>'archived product not found']);
        }
    }
    // show recent order
    function recent_order(){
        if (Session::has('admin')) {
            $orders = DB::table('orders')->
            leftJoin('products','orders.product_id','=','products.id')->
            orderBy('orders.id','desc')->limit(3)->get();
            $count_orders = DB::table('orders')->count();
            $form_categories = Products_category::all();
            $orders_inprogress = DB::table('orders')->where('delivery_status','in progress')->count();
            $orders_delivered = DB::table('orders')->where('delivery_status','delivered')->count();
            $orders_cancelled = DB::table('orders')->where('delivery_status','cancelled')->count();
             // orders for chart rendering - SQLite compatible
             $record = Order::select(
                 \DB::raw("COUNT(*) as count"), 
                 \DB::raw("strftime('%w', created_at) as day_name"),
                 \DB::raw("strftime('%d', created_at) as day")
             )
             ->where('created_at', '>', Carbon::today()->subDay(30))
             ->groupBy('day_name','day')
             ->orderBy('day')
             ->get();
           
              $data = [];
          
              foreach($record as $row) {
                 $data['label'][] = $row->day_name;
                 $data['data'][] = (int) $row->count;
               }
          
             $chart_data = json_encode($data);
            return view('dashboard', compact('orders','chart_data','count_orders','orders_delivered','orders_inprogress','orders_cancelled','form_categories'));

        }
        else {
            return redirect('/admin');
        }
        
        // return view('manage-categories', compact('orders','count_orders','orders_delivered','orders_inprogress','orders_cancelled'));
    }

    // show all orders
     function show_orders(){
        if (Session::has('admin')) {
            $orders = DB::table('orders')->
            leftJoin('products','orders.product_id','=','products.id')->
            orderBy('orders.id','desc')->limit(3)->get(); // orders 
            $allorders = DB::table('orders')->
            join('products','orders.product_id','=','products.id')
            ->select('orders.*','orders.id as order_id')
            ->orderBy('orders.id','desc')->paginate(10); // all paginated orders
            $count_orders = DB::table('orders')->count(); // count total orders
            $form_categories = Products_category::all();
            $orders_inprogress = DB::table('orders')->where('delivery_status','in progress')->count(); // count total orders in progress
            $orders_delivered = DB::table('orders')->where('delivery_status','delivered')->count(); // count total orders delivered
            $orders_cancelled = DB::table('orders')->where('delivery_status','cancelled')->count(); // count all cancelled orders
            // orders for chart rendering - SQLite compatible
            $record = Order::select(
                \DB::raw("COUNT(*) as count"), 
                \DB::raw("strftime('%w', created_at) as day_name"),
                \DB::raw("strftime('%d', created_at) as day")
            )
            ->where('created_at', '>', Carbon::today()->subDay(30))
            ->groupBy('day_name','day')
            ->orderBy('day')
            ->get();
          
             $data = [];
         
             foreach($record as $row) {
                $data['label'][] = $row->day_name;
                $data['data'][] = (int) $row->count;
              }
         
            $chart_data = json_encode($data);
            return view('list-orders', compact('orders','chart_data','allorders','count_orders','orders_delivered','orders_inprogress','orders_cancelled','form_categories'));

        }
        else {
            return redirect('/admin');
        }
        
        // return view('manage-categories', compact('orders','count_orders','orders_delivered','orders_inprogress','orders_cancelled'));
    }

    // update order functionalities
    function update_order(Request $req){
        $order_id = $req->order_id;
        $order = Order::find($order_id);
        $order->delivery_status  = $req->status;
        if($order->save()){
            return response()->json(['code'=>'success','msg'=>'updated']);
        }
        else{
            return response()->json(['code'=>'danger','msg'=>'error']);
        }
    }

    // ---------------------------------------------------------------------
    // API – return JSON for the front‑end
    // ---------------------------------------------------------------------

    public function apiProducts()
    {
        $products = Product::all();
        return response()->json($products);
    }

    public function apiCategories()
    {
        $categories = Products_category::all();
        return response()->json($categories);
    }

    public function apiOrders()
    {
        $orders = Order::all();
        return response()->json($orders);
    }
}
    
