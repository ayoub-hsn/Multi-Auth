<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userSessions = [];
        $sessions = $request->session()->all();

        // loop through all the sessions and group them by user ID
        foreach ($sessions as $key => $value) {
            if (strpos($key, 'user_') === 0) {
                $userId = explode('_', $key)[1];
                $userSessions[$userId][] = $key;
            }
        }

        return view('dashboard', [
            'user' => Auth::user(),
            'userSessions' => $userSessions
        ]);
    }

    public function logout(Request $request)
    {
        // clear all user sessions
        $userId = Auth::id();
        $sessions = $request->session()->all();
        foreach ($sessions as $key => $value) {
            if (strpos($key, 'user_' . $userId) === 0) {
                $request->session()->forget($key);
            }
        }

        Auth::logout();
        return redirect('/login');
    }
}
