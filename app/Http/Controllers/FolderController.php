<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Folder;
use App\Models\Project;
use App\Models\VisualizationRole;
use App\Providers\AuthServiceProvider;
use App\Providers\RoleServiceProvider;
use App\Rules\ValidityEspecialCharFolder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class FolderController extends Controller
{
    protected $projectController;
    /**
     * Constructor
     */
    public function __construct(ProjectController $projectController)
    {
        $this->projectController = $projectController;;
    }

    /**
     * Crear una carpeta
     */
    public function store($validityYear, $projectID)
    {
        $validator = Validator::make(request()->all(), [
            'name' => ['required', 'string', new ValidityEspecialCharFolder()],
            'visualizationRoleSelected' => 'required',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $project = Project::find($projectID);
        $path = "Vigencias/{$validityYear}/{$project->name}";
        if (request("folder_id") !==null) {
            $fatherFolder = Folder::find(request("folder_id"));
            $path = $fatherFolder->documentPath.'/'.$fatherFolder->name;
        }
        $folder = new Folder();
        $folder->name = request("name");
        $folder->project_id = $projectID;
        $folder->father_id = request("folder_id") !== null ? request("folder_id") : null;
        $folder->visualization_role_id = request("visualizationRoleSelected");
        $folder->documentPath = $path;
        $folder->save();

        return redirect()->back()->with("message", "Carpeta creada correctamente");
    }

    /**
     * Acceder a una carpeta
     */
    public function show($validityYear, $projectID, $folderID)
    {
        $project = Project::with('users')->find($projectID);
        $userRole = AuthServiceProvider::getRole();
        $folder = Folder::find($folderID);
        $childrenFolders = Folder::where('father_id', '=', $folder->id)->get();
        if ($userRole == RoleServiceProvider::INVESTIGATOR || $userRole == RoleServiceProvider::COLLABORATOR) {
            $usersID = $project->users->pluck('id')->toArray();
            $filteredDocuments = $folder->documents->filter(function ($document) use ($usersID) {
                $visualizationRole = $document->visualizationRole()->first()->name;
                return $this->projectController->conditionCollaboratorInvestigator($visualizationRole, $usersID);
            });
            $filteredFolders = $childrenFolders->filter(function ($folder) use ($usersID) {
                $visualizationRole = $folder->visualizationRole()->first()->name;
                return $this->projectController->conditionCollaboratorInvestigator($visualizationRole, $usersID);
            });
            $project->setRelation('documents', $filteredDocuments);
            $project->setRelation('folders', $filteredFolders);
        } elseif ($userRole == RoleServiceProvider::GUEST) {
            $filteredDocuments = $folder->documents->filter(function ($document) {
                $visualizationRole = $document->visualizationRole()->first()->name;
                return $this->projectController->conditionGuest($visualizationRole);
            });
            $filteredFolders = $childrenFolders->filter(function ($folder) {
                $visualizationRole = $folder->visualizationRole()->first()->name;
                return $this->projectController->conditionGuest($visualizationRole);
            });
            $project->setRelation('documents', $filteredDocuments);
            $project->setRelation('folders', $filteredFolders);
        } else {
            $project->setRelation('documents', $folder->documents);
            $project->setRelation('folders', $childrenFolders);
        }

        return Inertia::render("Projects/Project/Index", [
            // "folders" => $project->folders,
            // "documents" => $project->documents,
            "folder" => $folder,
            "project" => $project,
            "currentYear" => $validityYear,
            "visualizationsRole" => VisualizationRole::all(),
            "investigators" => InvestigatorController::getInvestigators(),
            "collaborators" => CollaboratorController::getCollaborators()
        ]);
    }

    /**
     * 
     * Crea un recurso compartido
     */
    public function storeSharedFolder()
    {
        $validator = Validator::make(request()->all(), [
            'name' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $folder = new Folder();
        $folder->name = request("name");
        $folder->project_id = null;
        $folder->father_id = null;
        $folder->visualization_role_id = RoleServiceProvider::GENERAL_PUBLIC_ID;
        $folder->save();

        return redirect()->back()->with("message", "Recurso compartido creado correctamente");
    }

    /**
     * Acceder a un recurso compartido
     */
    public function showSharedResource($folderID)
    {
        AuthServiceProvider::getRole();
        $folder = Folder::with("documents")->find($folderID);
        if (isset($folder->project_id) || isset($folder->father_id)) {
            abort(403, 'No tienes los permisos para estar aqui');
            return;
        }
        return Inertia::render("SharedFolder/Index", [
            "documents" => Document::with(["folder", "project.validity", "project"])->get(),
            "folder" => $folder,
            "childrenFolders" => Folder::where("father_id", "=", $folderID)->get()
        ]);
    }


    /**
     * Actualizar carpeta
     */
    public function updateFolder($projectID, $folderID)
    {
        $project = Project::find($projectID);
        $usersID =  $project->users->pluck('id')->toArray();
        $role = AuthServiceProvider::getRole();

        if (!in_array(Auth::user()->id, $usersID) && $role != RoleServiceProvider::ADMIN) {
            abort(403, "No tienes persmisos para estar aqui");
        }

        return $this->update($folderID);
    }

    /**
     * Actualizar una carpeta dentro del recurso compartido
     */
    public function updateSharedFolder($folderID)
    {
        return $this->update($folderID);
    }

    /**
     * Actualizar cualquier carpeta
     */
    private function update($folderID)
    {
        $validator = Validator::make(request()->all(), [
            'name' => ['required', 'string', new ValidityEspecialCharFolder()],
            'visualizationRoleSelected' => 'numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $folder = Folder::find($folderID);
        $childrenFolders = Folder::where("father_id", $folder->id)->get();
        if ($childrenFolders !== null) {
            foreach ($childrenFolders as $cf) {
                $path = $cf->documentPath;
                $explode = explode("/", $path);
                $explode[count($explode)-1] = request("name");
                $cf->documentPath = implode("/", $explode);
                $cf->update();
            }
        }
        $folder->name = request("name");
        if (request("visualizationRoleSelected") !== null) {
            $folder->visualization_role_id = request("visualizationRoleSelected");
        }
        $folder->update();

        return redirect()->back()->with("message", "Carpeta actualizada correctamente");
    }


    /**
     * Eliminar carpeta
     */
    public function destroyFolder($projectID, $folderID)
    {
        $project = Project::find($projectID);
        $usersID =  $project->users->pluck('id')->toArray();
        $role = AuthServiceProvider::getRole();

        if (!in_array(Auth::user()->id, $usersID) && $role != RoleServiceProvider::ADMIN) {
            abort(403, "No tienes persmisos para estar aqui");
        }
        return $this->destroy($folderID);
    }

    /**
     * Eliminar recurso compartido
     */
    public function destroy($folderID)
    {
        $folder = Folder::find($folderID);
        $childrenFolders = Folder::where('father_id', '=', $folder->id)->get();
        if ($folder->documents->count() > 0 || $childrenFolders->count() > 0){
            return redirect()->back()->with("errorMessage", "¡No se puede eliminar una carpeta con documentos o carpetas asociados!");
        }

        $folder->delete();

        return redirect()->back()->with("message", "Carpeta eliminada correctamente");
    }
}
