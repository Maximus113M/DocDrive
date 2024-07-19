<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        return Inertia::render('Auth/ForgotPassword', [
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        
        $email = $request->get("email");
        $token = Hash::make($email . "|" . now());
        $encodedToken = base64_encode($token);
        $url = url('/reset-password/' . $encodedToken); 
        $existsToken = DB::table("password_resets")->where("email", $email)->exists();
        if ($existsToken) {
            DB::table("password_resets")->where("email", $email)->delete();
        }
        DB::table("password_resets")->insert([
            "email" => $email,
            "token" => $encodedToken,
            "created_at" => now(),
        ]);

        $user = User::where("email", $email)->first();

        Mail::to($user)->send(new ResetPasswordMail($url, $user->name));
        
        return redirect()->route('login')->with('message', 'Te hemos enviado un enlace a tu correo para restablecer tu contraseña!');

    }
}
