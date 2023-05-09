<?php

namespace Modules\User\Http\Controllers;

use Inertia\Inertia;
use Modules\Core\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Modules\User\Models\User;

class AuthController extends Controller
{
    public function register()
    {
        return Inertia::render('User/Register');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        auth()->login($user);

        $request->session()->regenerate();

        return redirect()->route('admin.service.index');
    }

    public function login()
    {
        return Inertia::render('User/Login');
    }

    public function auth(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (auth()->attempt([
            'password' => $request->password,
            'email' => $request->email,
        ])) {
            $request->session()->regenerate();
            return redirect()->route('admin.service.index');
        } else {
            return Inertia::render('User/Login', [
                'failed' => "These credentials do not match any of our records!"
            ]);
        }
    }

    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function emailVerification()
    {
        if (auth()->user()->hasVerifiedEmail()) {
            return redirect()->back();
        }
        return Inertia::render('User/VerifyEmail');
    }

}
