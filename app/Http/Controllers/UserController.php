<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use Session;

class UserController extends Controller
{
    // register functionality
    function register(Request $req){
        // validate registration
        $validate = $req->validate([
            'first_name'=> 'required',
            'last_name'=> 'required',
            'address'=> 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5',
            'phone' => 'required|numeric|digits:11',
        ]);

        $user = new User;
        $user->first_name = $req->first_name;
        $user->last_name = $req->last_name;
        $user->address = $req->address;
        $user->email = $req->email;
        $user->phone = $req->phone;
        $user->password = Hash::make($req->password);
        $user->save();
        
        // Login the user immediately
        \Auth::login($user);
        
        return redirect('/');
    }
    // login functionality
    function login(Request $req){
        // validate login
        $validate = $req->validate([
            'email'=> 'required|max:150',
            'password' => 'required',
        ]);

        if (\Auth::attempt(['email' => $req->email, 'password' => $req->password])) {
            return redirect('/');
        }
        else{
            return back()->with('failure','incorrect username or password');
        }
    }

    // user data
    function user_data(){
        if(\Auth::check()){
            $user_id = \Auth::id();
            $data = User::find($user_id);
            // return view("cartlist",['user_data'=>$data]);
        }
    }

    // Show user profile
    function profile(){
        if(\Auth::check()){
            $user = \Auth::user();
            $orderCount = \DB::table('orders')->where('user_id', $user->id)->count();
            return view('profile', ['user' => $user, 'orderCount' => $orderCount]);
        }
        return redirect('/login');
    }

    // Update profile
    function updateProfile(Request $req){
        if(!\Auth::check()){
            return redirect('/login');
        }

        $validate = $req->validate([
            'first_name'=> 'required',
            'last_name'=> 'required',
            'phone' => 'required|numeric|digits:11',
            'address'=> 'required',
        ]);

        $user = \Auth::user();
        $user->first_name = $req->first_name;
        $user->last_name = $req->last_name;
        $user->phone = $req->phone;
        $user->address = $req->address;
        $user->save();

        return back()->with('success', 'Profile updated successfully!');
    }
    function forgotPassword(Request $req){
        // Logic to send email would go here
        // For now, just return a success message
        return back()->with('success', 'If your email exists in our system, you will receive a password reset link shortly.');
    }
}
