<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

class HomeController extends Controller
{
    public function index(){
        return view('admin.Dashboard');

        
       //$admin= Auth::guard('admin')->user();
        //echo 'Welcome' .$admin->name. '<a href="'.route('admin.logout').'">Logout</a>';
        

    }

    public function logout(){
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');

    }
    
}
    
