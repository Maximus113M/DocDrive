<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Providers\AuthServiceProvider;
use App\Providers\RoleServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password as RulesPassword;
use Inertia\Inertia;

class CollaboratorController extends Controller
{
    /**
     *
     * Muestra todos los colaboradores
     */
    public function index()
    {
        $query = DB::table("users AS u")
            ->select('u.name AS name', 'u.id AS id', 'u.email AS email')
            ->join("roles AS r", "u.role_id", "=", "r.id")
            ->where("r.name", "=", RoleServiceProvider::COLLABORATOR)
            ->get();
        
        return Inertia::render("Collaborator/Index", [
            "collaborators" => $query,
            "isAuthenticated" => AuthServiceProvider::checkAuthenticated(),
            "role" => AuthServiceProvider::getRole()
        ]);
    }


     /**
     * Crea un nuevo Colaborator
     */
    public function store()
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', RulesPassword::defaults()],
        ]);

        if ($validator->fails()) {
            return redirect()->route("collaborator.index")->withErrors($validator)->withInput();
        }

        $user = new User();
        $user->name = request("name");
        $user->email = request("email");
        $user->password = Hash::make(request("password"));
        $user->role_id = RoleServiceProvider::COLLABORATOR_ID;

        $user->save();

        $user->assignRole(RoleServiceProvider::COLLABORATOR);

        return redirect()->route("collaborator.index")->with("message", "¡El colaborador se ha creado correctamente!");
    }

     /**
     * Actualiza un colaborador
     */
    public function update($userID)
    {
        $this->validate(request(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', RulesPassword::defaults()],
        ]);

        $user = User::find($userID);

        $user->update([
            'name' => request("name"),
            'email' => request("email"),
            'password' => Hash::make(request("password")),
        ]);

        return redirect()->route("collaborator.index")->with("message", "¡El Colaborador se ha actualizado correctamente!");
    }

    /**
     * Eliminar un colaborador
     * 
     */
    public function destroy($userID)
    {
        $user = User::find($userID);

        $user->delete();

        return redirect()->route("collaborator.index")->with("message", "¡El Colaborador se ha eliminado correctamente!");
    }

}
