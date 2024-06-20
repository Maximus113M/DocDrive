<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\Project;
use App\Models\VisualizationRole;
use App\Providers\AuthServiceProvider;
use App\Providers\RoleServiceProvider;
use Illuminate\Http\Request;
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
            'name' => ['required', 'string'],
            'visualizationRoleSelected' => 'required',
            'folder' => 'number'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $folder = new Folder();
        $folder->name = request("name");
        $folder->project_id = request("folder_id") !== null ? null : $projectID;
        $folder->father_id = request("folder_id")!== null ? request("folder_id") : null;
        $folder->visualization_role_id = request("visualizationRoleSelected");
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
}
