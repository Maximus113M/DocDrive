<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Providers\AuthServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use App\Models\DocumentCategory;

class DocumentCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render("DocumentCategories/Index", [
            "categories" => DocumentCategory::all(),
            "isAuthenticated" => AuthServiceProvider::checkAuthenticated(),
            "role" => AuthServiceProvider::getRole()
        ])->with("currentRoute", 'categories');
    }


    public function store()
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required|string|max:100|unique:document_categories',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DocumentCategory::create([
            "name" => request('name')
        ]);

        return redirect()->back()->with("message", "Categoria creada.");
    }


    public function update($categoryId)
    {   
        if($categoryId == 1){
            return redirect()->back()->with("message", "Esta categoría no puede ser actualizada.");
        }

        $validator = Validator::make(request()->all(), [
            'name' => 'required|string|max:100|unique:document_categories,name,'.$categoryId,
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $category= DocumentCategory::find($categoryId);

        if (!$category) {
            abort(404, "No se encontró la categoría seleccionada");
        }
        $category->update([
            "name" => request('name')
        ]);

        return redirect()->back()->with("message", "Categoría actualizada.");
    }


    public function destroy($categoryId)
    {
        if($categoryId == 1){
            return redirect()->back()->with("message", "Esta categoría no puede ser eliminada.");
        }

        $category= DocumentCategory::find($categoryId);

        if (!$category) {
            abort(404, "No existe la categoría seleccionada");
        }

        $records= DB::table("document_categories_documents")->select('*')->where('category_id','=',$categoryId)->get();
        if($records->count() > 0){
            return redirect()->back()->with("message", "No puedes eliminar la categoría mientras haya documentos asociados");
        }else{
            $category->delete();
            return redirect()->back()->with("message", "Categoría eliminada.");
        }
    }
}
