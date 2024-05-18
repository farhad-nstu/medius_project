<?php

namespace App\Http\Controllers;

use App\Enums;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request) {
        if($request->method() == "POST"){
            $user = new User();
            $user->name = $request->name;
            $user->account_type = $request->account_type;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();
            return back()->with('success', 'Register successfully');
        }
        $data['account_types'] = Enums::ACCOUNT_TYPES;
        return view('auth.register', $data);
    }

    public function login(Request $request) {
        if($request->method() == "POST"){
            $credetials = [
                'email' => $request->email,
                'password' => $request->password,
            ];
            if (Auth::attempt($credetials)) {
                return redirect('/home')->with('success', 'Successfully Login');
            }
            return back()->with('error', 'Email or Password Not Matched!');
        }
        return view('auth.login');
    }

    public function logout() {
        Auth::logout();
        return redirect()->route('login');
    }
}
