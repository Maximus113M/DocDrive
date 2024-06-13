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

        $document = new Folder();
        $document->name = request("name");
        $document->project_id = $projectID;
        $document->visualization_role_id = request("visualizationRoleSelected");
        $document->save();

        return redirect()->back()->with("message", "Carpeta creada correctamente");
    }
}
