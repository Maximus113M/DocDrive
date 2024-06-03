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
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;

use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class InvestigatorController extends Controller
{
    /**
     * Obtener los investigadores
     * 
     */
    static public function getInvestigators()
    {
        $query = DB::table("users AS u")
            ->select('u.name AS name', 'u.id AS id', 'u.document AS document', 'u.phone AS phone', 'u.email AS email')
            ->join("roles AS r", "u.role_id", "=", "r.id")
            ->where("r.name", "=", RoleServiceProvider::INVESTIGATOR)
            ->get();

        return $query;
    }

    /**
     *
     * Muestra todos los investigadores
     */
    public function index()
    {
        error_log('Enter in Investigator/Index');
        return Inertia::render("Investigator/Index", [
            "investigators" => InvestigatorController::getInvestigators(),
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
            'document' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->route("investigator.index")->withErrors($validator)->withInput();
        }

        $password = AuthServiceProvider::generatePassword();
        
        $user = User::create([
            'name' =>  request("name"),
            'document' => request("document"),
            'phone' => request("phone"),    
            'email' => request("email"),
            'password' => Hash::make($password),
            'role_id' => RoleServiceProvider::INVESTIGATOR_ID,
        ]);

        $user->assignRole(RoleServiceProvider::INVESTIGATOR);

        //Mail::to($user)->send(new VerifyEmail($password, $user));

        return Redirect::route("investigator.index")->with("message", "¡El investigador se ha creado correctamente!");
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
        try {
            error_log('Enter in Investigator/Destroy');
            
            $user = User::find($userID);

            $user->delete();

            return redirect()->route("investigator.index")->with("successDelete", true);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

}
