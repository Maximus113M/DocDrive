<?php

namespace App\Http\Controllers;

use App\Mail\VerifyEmail;
use App\Models\User;
use App\Providers\AuthServiceProvider;
use App\Providers\RoleServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class InvestigatorController extends Controller
{
    /**
     *
     * Muestra todos los investigadores
     */
    public function index()
    {
        $query = DB::table("users AS u")
            ->select('u.name AS name', 'u.id AS id', 'u.email AS email')
            ->join("roles AS r", "u.role_id", "=", "r.id")
            ->where("r.name", "=", RoleServiceProvider::INVESTIGATOR)
            ->get();
        
        return Inertia::render("Investigator/Index", [
            "investigators" => $query,
            "isAuthenticated" => AuthServiceProvider::checkAuthenticated(),
            "role" => AuthServiceProvider::getRole()
        ]);
    }


    /**
     * Crea un nuevo Investigador
     */
    public function store()
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            //'password' => ['required', Password::defaults()],
        ]);

        if ($validator->fails()) {
            return redirect()->route("investigator.index")->withErrors($validator)->withInput();
        }

        $password = AuthServiceProvider::generatePassword();

        $user = new User();
        $user->name = request("name");
        $user->email = request("email");
        $user->password = Hash::make($password);
        $user->role_id = RoleServiceProvider::INVESTIGATOR_ID;

        $user->save();

        $user->assignRole(RoleServiceProvider::INVESTIGATOR);

        Mail::to($user)->send(new VerifyEmail($password, $user));

        return redirect()->route("investigator.index")->with("message", "¡El investigador se ha creado correctamente!");
    }

    /**
     * Actualiza un Investigador
     */
    public function update($userID)
    {
        $this->validate(request(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', Password::defaults()],
        ]);

        $user = User::find($userID);

        $user->update([
            'name' => request("name"),
            'email' => request("email"),
            'password' => Hash::make(request("password")),
        ]);

        return redirect()->route("investigator.index")->with("message", "¡El investigador se ha actualizado correctamente!");
    }

    /**
     * Eliminar un investigador
     * 
     */
    public function destroy($userID)
    {
        $user = User::find($userID);

        $user->delete();

        return redirect()->route("investigator.index")->with("message", "¡El investigador se ha eliminado correctamente!");
    }

}
