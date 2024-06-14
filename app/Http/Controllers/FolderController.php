<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FolderController extends Controller
{
    /**
     * Crear una carpeta
     */
    public function store($validityYear, $projectID)
    {
        $validator = Validator::make(request()->all(), [
            'name' => ['required', 'string'],
            'visualizationRoleSelected' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $folder = new Folder();
        $folder->name = request("name");
        $folder->project_id = $projectID;
        $folder->visualization_role_id = request("visualizationRoleSelected");
        $folder->save();

        return redirect()->back()->with("message", "Carpeta creada correctamente");
    }
}
