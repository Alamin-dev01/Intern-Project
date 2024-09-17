<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminLoginController extends Controller
{
    public function index()
    {
        return view('admin.login');
    }

    public function authenticate(Request $request)
    {
        // Validate the form data
        $validator=Validator::make($request->all(), 
      [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if($validator->passes()){
        
        // Attempt to log the user in
        // $credentials = $request->only('email', 'password');
        if (Auth::guard('admin')->attempt(['email'=> $request->email, 'password'=> $request->password], $request->get('remember'))) {
            $admin = Auth::guard('admin')->user();
            if($admin->role == 2){
                return redirect()->route('admin.dashboard');
            } else{

                Auth::guard('admin')->logout();
                return redirect()->route('admin.login')->with ('error', 'You are not authorized to access admin panel');
            }
            // Authentication passed, redirect to the intended page
            return redirect()->route('admin.dashboard');
        } else {
            // If authentication fails, redirect back to the login form with an error
            return redirect()->route('admin.login')->with('error', 'Either Email/Password is incorrect');
        }
    } else {
        return redirect()->route('admin.login')
        ->withErrors($validator)
        ->withInput($request->only('email'));
    }

}

}

