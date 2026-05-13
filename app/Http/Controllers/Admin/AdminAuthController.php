<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class AdminAuthController extends Controller
{
    const SESSION_KEY = 'admin_user_id';
    const REMEMBER_COOKIE = 'admin_remember_token';
    const REMEMBER_DAYS = 30;

    public function loginPage(Request $request)
    {
        if (Session::get(self::SESSION_KEY)) {
            return redirect('/admin/dashboard');
        }

        // Check remember me cookie
        $token = $request->cookie(self::REMEMBER_COOKIE);
        if ($token) {
            $user = User::where('remember_token', $token)
                ->where('role', 'admin')
                ->where('is_active', true)
                ->first();

            if ($user) {
                Session::put(self::SESSION_KEY, $user->id);
                return redirect('/admin/dashboard');
            }
        }

        return view('admin.login', ['error' => null]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)
            ->where('role', 'admin')
            ->where('is_active', true)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return view('admin.login', ['error' => 'Email atau password salah']);
        }

        Session::put(self::SESSION_KEY, $user->id);

        $redirect = redirect('/admin/dashboard');

        // Handle remember me
        if ($request->has('remember')) {
            $token = Str::random(60);
            $user->update(['remember_token' => $token]);
            $redirect = $redirect->withCookie(
                Cookie::make(self::REMEMBER_COOKIE, $token, self::REMEMBER_DAYS * 24 * 60)
            );
        }

        return $redirect;
    }

    public function logout()
    {
        $userId = Session::get(self::SESSION_KEY);
        if ($userId) {
            User::where('id', $userId)->update(['remember_token' => null]);
        }

        Session::flush();

        return redirect('/admin/login')->withCookie(
            Cookie::forget(self::REMEMBER_COOKIE)
        );
    }

    public static function getAdmin(Request $request): ?User
    {
        $userId = Session::get(self::SESSION_KEY);
        if (!$userId) {
            return null;
        }

        return User::where('id', $userId)
            ->where('role', 'admin')
            ->where('is_active', true)
            ->first();
    }
}
