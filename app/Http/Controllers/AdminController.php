<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Hash;
use Session;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Products_category;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    // ... (existing code)

    // fetch admin details and dashboard data
    public function admin_details()
    {
        if (Session::has('admin')) {
            $id = Session::get('admin')['id'];
            $admin = Admin::find($id);
            // recent orders
            $orders = DB::table('orders')
                ->leftJoin('products', 'orders.product_id', '=', 'products.id')
                ->orderBy('orders.id', 'desc')
                ->limit(3)
                ->get();
            $count_orders = DB::table('orders')->count();
            $orders_inprogress = DB::table('orders')->where('delivery_status', 'in progress')->count();
            $orders_delivered = DB::table('orders')->where('delivery_status', 'delivered')->count();
            $orders_cancelled = DB::table('orders')->where('delivery_status', 'cancelled')->count();
            $form_categories = Products_category::all();
            $record = Order::select(
                DB::raw('COUNT(*) as count'),
                DB::raw("strftime('%w', created_at) as day_name"),
                DB::raw("strftime('%d', created_at) as day")
            )
                ->where('created_at', '>', Carbon::today()->subDay(30))
                ->groupBy('day_name', 'day')
                ->orderBy('day')
                ->get();
            $data = [];
            foreach ($record as $row) {
                $data['label'][] = $row->day_name;
                $data['data'][] = (int) $row->count;
            }
            $chart_data = json_encode($data);
            return view('dashboard', compact('admin', 'orders', 'chart_data', 'count_orders', 'orders_delivered', 'orders_inprogress', 'orders_cancelled', 'form_categories'));
        }
        return redirect('/admin');
    }

    // profile picture upload
    public function profile_picture(Request $req)
    {
        if (Session::has('admin')) {
            $req->validate([
                'image' => 'required|image|max:2048|mimes:jpeg,jpg,png,gif,svg',
            ]);
            $imageName = time() . '.' . $req->image->extension();
            $id = Session::get('admin')['id'];
            $admin = Admin::find($id);
            $admin->image = $imageName;
            $admin->save();
            if ($req->image->move(public_path('assets/images'), $imageName)) {
                return back()->with('success', 'Image uploaded successfully');
            }
        }
        return redirect('/admin');
    }
}
