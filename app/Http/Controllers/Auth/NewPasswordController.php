<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function create(Request $request)
    {
        $token = $request->route('token');
        $existsToken = DB::table("password_resets")->where("token", "=", $token)->exists();
        if (!$existsToken) {
            return redirect()->route('login')->with('errorMessage', 'El enlace de restablecimiento de contraseña es inválido.');
        }

        return Inertia::render('Auth/ResetPassword', [
            'token' => $request->route('token'),
        ]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $email = $request->get("email");
        $emailFromToken = DB::table("password_resets")->where("token", $request->get("token"))
            ->select("email");
        
        if ($emailFromToken->first()->email!= $email) {
            return redirect()->back()->with('errorMessage', 'El email indicado es incorrecto.');
        }

        $user = User::where("email", "=", $email)->first();
        $user->password = Hash::make($request->get("password"));
        $user->update();

        DB::table("password_resets")->where("token", $request->get("token"))->delete();
    
        return redirect()->route('login')->with('message', 'Contraseña restablecida con éxito. Ahora puedes iniciar sesión.');
    }
}
