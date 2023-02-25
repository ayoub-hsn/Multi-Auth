<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if(Auth::check()){
            return back();
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            // Set the session ID to include the user's ID
            session(['user_' . Auth::user()->id => session()->getId()]);

            // Redirect the user to the dashboard
            return redirect('/dashboard');
        } else {
            return redirect('/login')
                ->withInput()
                ->withErrors(['email' => 'Invalid email or password']);
        }
    }

    public function logout(Request $request)
    {
        $sessionId = $request->session()->get('user_' . auth()->id());

        if ($sessionId) {
            DB::table('sessions')->where('id', $sessionId)->delete();
        }

        auth()->logout();

        return redirect()->route('login');
    }


}
