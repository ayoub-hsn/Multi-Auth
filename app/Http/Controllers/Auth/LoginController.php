<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        // if(Auth::check()){
        //     return back();
        // }
        $users = User::all();
        return view('auth.login',compact('users'));
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

    public function switchUser(Request $request, $userId)
    {
        $user = User::find($userId);

        if ($user) {
            // Store the current user ID in the session
            $currentUserId = Auth::id();
            Session::put('current_user_id', $currentUserId);

            // Log in the new user
            Auth::login($user);

            // Remove the new user's session ID from the list of connected user session IDs
            $sessionIds = Session::get('user_session_ids', []);
            $newSessionId = Session::getId();
            $key = array_search($newSessionId, $sessionIds);
            if ($key !== false) {
                unset($sessionIds[$key]);
                Session::put('user_session_ids', $sessionIds);
            }

            // Add the new user's session ID to the list of connected user session IDs
            $sessionIds[] = $newSessionId;
            Session::put('user_session_ids', $sessionIds);

            return redirect()->route('dashboard');
        } else {
            abort(404);
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
