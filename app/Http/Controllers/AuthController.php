<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Backtrace\Arguments\ReducedArgument\ReducedArgument;

class AuthController extends Controller
{
    public function login(){
        return view('front.account.login');


    }

    public function register(){
        return view('front.account.register');

    }

    public function processRegister(Request $request){
        $validator=Validator::make($request->all(),[
            'name'=>'required|min:3',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:5|confirmed'
        ]);

        if($validator->passes()){

            $user= new User;
            $user->name= $request->name;
            $user->email= $request->email;
            $user->phone= $request->phone;
            $user->password= Hash::make($request->password);
            $user->save();

            session()->flash('success', 'You have been registered successfully');
            return response()->json([
                'status'=>true,
            ]);

        } else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);
        }
    }

    public function authenticate(Request $request) {
        // Validate the input fields
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        // If validation passes
        if ($validator->passes()) {
            // Attempt to log the user in with the given credentials
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {
                
                // Check if there's an intended URL in the session
                if (session()->has('url.intended')) {
                    // Redirect to the intended URL (e.g., checkout)
                    return redirect(session('url.intended'));
                }
    
                // If no intended URL, redirect to profile page
                return redirect()->route('account.profile');
    
            } else {
                // If authentication fails
                return redirect()->route('account.login')
                                 ->withInput($request->only('email'))
                                 ->with('error', 'Either email/password is incorrect');
            }
        } else {
            // If validation fails
            return redirect()->route('account.login')
                             ->withErrors($validator)
                             ->withInput($request->only('email'));
        }
    }
    
        
public function profile(){

    return view('front.account.profile');
}

public function logout(){
    Auth::logout(); // This line will log the user out..... I was trying for an hour for fixing this error... and it's comes put with this little mistake!!!!!!
    return redirect()->route('account.login')
    ->with('success','You successfully logged out!');
}
    }
        


