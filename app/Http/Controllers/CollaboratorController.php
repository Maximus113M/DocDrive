<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Mail\VerifyEmail;
use App\Models\User;
use App\Providers\AuthServiceProvider;
use App\Providers\RoleServiceProvider;
use App\Rules\SingleEmailUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password as RulesPassword;
use Inertia\Inertia;

class CollaboratorController extends Controller
{
    protected $userController;

    /**
     * Constructor
     * 
     */
    public function __construct(RegisteredUserController $userController)
    {
        $this->userController = $userController;
    }


    /**
     *
     * Muestra todos los colaboradores
     */
    public function index()
    {
        $query = DB::table("users AS u")
            ->select('u.name AS name', 'u.id AS id', 'u.document AS document', 'u.phone AS phone', 'u.email AS email')
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
            'document' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->route("collaborator.index")->withErrors($validator)->withInput();
        }

        $password = AuthServiceProvider::generatePassword();

        $user = User::create([
            'name' =>  request("name"),
            'document' => request("document"),
            'phone' => request("phone"),    
            'email' => request("email"),
            'password' => Hash::make($password),
            'role_id' => RoleServiceProvider::COLLABORATOR_ID,
        ]);

        $user->assignRole(RoleServiceProvider::COLLABORATOR);

        Mail::to($user)->send(new VerifyEmail($password, $user));

        return redirect()->route("collaborator.index")->with("message", "¡El colaborador se ha creado correctamente!");
    }

     /**
     * Actualiza un colaborador
     */
    public function update($userID)
    {
        return $this->userController->update($userID, RoleServiceProvider::COLLABORATOR);
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
