<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\AuthServiceProvider;
use App\Providers\RoleServiceProvider;
use App\Providers\RouteServiceProvider;
use App\Rules\SingleEmailUser;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class RegisteredUserController extends Controller
{

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

    /**
     * 
     * Ruta para mostrar la vista para editar el usuario
     */
    public function edit()
    {
        $user = Auth::user();
        $user->role;
        return Inertia::render("Profile/Edit", [
            "user" => $user
        ]);
    }


    /**
     * Actualizar cualquier tipo de usuario
     */
    public function update($userID, $role)
    {
        $route = null;
        $message = null;
        $password = null;
        if ($role == RoleServiceProvider::INVESTIGATOR) {
            $route = "investigator.index";
            $message = "¡El investigador se ha actualizado correctamente!";
        } elseif ($role == RoleServiceProvider::COLLABORATOR) {
            $route = "collaborator.index";
            $message = "¡El colaborador se ha actualizado correctamente!";
        } else {
            $route = "user.edit";
            $message = "¡Has actualizado correctamente tus datos!";
        }


        $validator = Validator::make(request()->all(), [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', new SingleEmailUser($userID)],
            'phone' => ['required'],
            'document' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route($route)->withErrors($validator)->withInput();
        }


        $user = User::find($userID);
        if (!$password) {
            $password = $user->password;
        }

        error_log($password);


        $user->name = request("name");
        $user->document = request("document");
        $user->phone = request("phone");
        $user->email = request("email");
        $user->password = $password;
        $user->update();

        return redirect()->route($route)
            ->with("message", $message);
    }

    /**
     * ACtualiz los datos del usuario autenticado
     */
    public function updateProfile()
    {
        return $this->update(Auth::user()->id, null);
    }

    /**
     * Metodo para actualizar la contraseña
     */
    public function changePassword()
    {
        $validator = Validator::make(request()->all(), [
            'oldPassword' => ['required', Password::defaults()],
            'password' => ['required', Password::defaults()],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::find(Auth::user()->id);

        if (!Hash::check(request("oldPassword"), $user->password)) {
            return redirect()->back()
                ->with("errorMessage", "¡La contraseña actual es incorrecta!");
        }

        $user->password = Hash::make(request("password"));
        $user->update();

        return redirect()->route("user.edit")
            ->with("message", "¡La contraseña se ha actualizado correctamente!");
    }
}
