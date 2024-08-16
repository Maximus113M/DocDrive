<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CollaboratorController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\InvestigatorController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\DocumentCategoryController;
use App\Http\Controllers\ValidityController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



// RUTA DE LAS VIGENCIAS
Route::get('/', [ValidityController::class, "index"])->name('validity.index');

// RUTA PARA MOSTRAR LOS PROYECTOS DE UNA VIGENCIA
Route::get('/{validityYear}/projects', [ValidityController::class, "projects"])->name('validity.projects');

// RUTA PARA MOSTRAR LOS DOCUMENTOS O CARPETAS DE UN PROYECTO
Route::get('/{validityYear}/projects/{projectID}', [ProjectController::class, 'index'])
    ->name('project.index')->middleware("protect.project");

// RUTA PARA SUBIR LOS DOCUMENTOS
Route::post('/{validityYear}/projects/{projectID}/upload', [DocumentController::class, 'store'])
    ->name('document.upload')->middleware(["protect.project", 'auth']);

// RUTA PARA SUBIR LAS CARPETAS
Route::post('/{validityYear}/projects/{projectID}/folder', [FolderController::class, 'store'])
    ->name('folder.upload')->middleware(["protect.project", "auth"]);

// RUTA PARA CREAR LINKS
Route::post('/{validityYear}/projects/{projectID}/link', [DocumentController::class, 'storeLink'])
    ->name('link.add')->middleware(["protect.project", "auth"]);

// RUTA PARA MOSTRAR LOS DOCUMENTOS O CARPETAS DE UNA CARPETA
Route::get('/{validityYear}/projects/{projectID}/folders/{folderID}', [FolderController::class, 'show'])
    ->name('folder.index')->middleware("protect.documents");

// RUTA PARA VISUALIZAR EL DOCUMENTO
Route::get('/{validityYear}/projects/{projectID}/files/{documentID}', [DocumentController::class, 'showProtected'])
    ->name('file.index')->middleware("protect.documents");

// RUTA PARA VISUALIZAR LOS DOCUMENTOS O CARPETAS DE UN RECURSO COMPARTIDO
Route::get('/shared/{folderID}', [FolderController::class, 'showSharedResource'])
    ->name('shared.index');

// RUTA PARA VISUALIZAR LOS DOCUMENTOS QUE ESTAN DENTRO DEL RECRUSO COMPARTIDO
Route::get('/shared/{folderID}/document/{documentID}', [DocumentController::class, 'showSharedResource'])
    ->name('shared.file.index');

// RUTA PARA HACER LA BUSQUEDA
Route::get('/search/{consulta}', [ValidityController::class, 'search'])
    ->name('search.index');

// RUTAS PROTEGIDAS PARA EL ADMIN
Route::middleware(['auth', 'role:admin'])->group(function () {

    // RUTA PARA CREAR UNA VIGENCIA
    Route::post('/validity/store', [ValidityController::class, 'store'])
        ->name('validity.store');

    // RUTA PARA EDITAR UNA VIGENCIA
    Route::put('/{validityID}/update', [ValidityController::class, 'update'])
        ->name('validity.update');

    // RUTA PARA ELIMINAR UNA VIGENCIA
    Route::delete('/{validityID}/destroy', [ValidityController::class, 'destroy'])
        ->name('validity.destroy');

    // RUTA PARA MOSTRAR LOS INVESTIGADORES
    Route::get('/investigator/index', [InvestigatorController::class, 'index'])
        ->name('investigator.index');

    // RUTA PARA CREAR UN INVESTIGADOR
    Route::post('/investigator/store', [InvestigatorController::class, 'store'])
        ->name('investigator.store');

    // RUTA PARA ELIMINAR UN INVESTIGADOR
    Route::delete('/investigator/{userID}/destroy', [InvestigatorController::class, 'destroy'])
        ->name('investigator.destroy');

    // RUTA PARA CREAR PROYECTOS
    Route::post('/project/store', [ProjectController::class, 'store'])
        ->name('project.store');

    // RUTA PARA CREAR UN RECURSO COMPARTIDO
    Route::post('/shared/folder/store', [FolderController::class, 'storeSharedFolder'])
        ->name('shared.folder.store');

    //DOCUMENT CATEGORIES
    // RUTA PARA MOSTRAR CATEGORIAS DE DOCUMENTOS
    Route::get('/document-category/index', [DocumentCategoryController::class, 'index'])
        ->name('document-category.index');
    // RUTA PARA CREAR UNA CATEGORIA DE DOCUMENTO
    Route::post('/document-category/store', [DocumentCategoryController::class, 'store'])
        ->name('document-category.store');
    // RUTA PARA EDITAR UNA CATEGORIA DE DOCUMENTO
    Route::put('/document-category/{categoryID}/update', [DocumentCategoryController::class, 'update'])
        ->name('document-category.update');
    // RUTA PARA ELIMINAR UNA CATEGORIA DE DOCUMENTO
    Route::delete('/document-category/{categoryID}/destroy', [DocumentCategoryController::class, 'destroy'])
        ->name('document-category.destroy');
});


// RUTAS PROTEGIDAS EN COMUN DEL ADMIN Y EL INVESTIGADOR
Route::middleware(['auth', 'role:admin|investigator'])->group(function () {

    // RUTA PARA EDITAR UN INVESTIGADOR
    Route::put('/investigator/{userID}/update', [InvestigatorController::class, 'update'])
        ->name('investigator.update');

    // RUTA PARA EDITAR UN COLABORADOR
    Route::put('/collaborator/{userID}/update', [CollaboratorController::class, 'update'])
        ->name('collaborator.update');


    // RUTA PARA MOSTRAR LOS COLABORADORES
    Route::get('/collaborator/index', [CollaboratorController::class, 'index'])
        ->name('collaborator.index');

    // RUTA PARA CREAR UN COLABORADOR
    Route::post('/collaborator/store', [CollaboratorController::class, 'store'])
        ->name('collaborator.store');

    // RUTA PARA ELIMINAR UN COLABORADOR
    Route::delete('/collaborator/{userID}/destroy', [CollaboratorController::class, 'destroy'])
        ->name('collaborator.destroy');

    // RUTA PARA ACTUALIZAR UN PROYECTO
    Route::put('/project/{projectID}/update', [ProjectController::class, 'update'])
        ->name('project.update');

    // RUTA PARA ELIMINAR UN PROYECTO
    Route::delete('/project/{projectID}/destroy', [ProjectController::class, 'destroy'])
        ->name('project.destroy');

    // RUTA PARA ASOCIAR USUARIOS A UN PROYECTO
    Route::post('/project/{projectID}/users', [ProjectController::class, 'associatedUsers'])
        ->name('project.associated.users');

    // RUTA PARA ACTUALIZAR UNA CARPETA
    Route::put('project/{projectID}/folder/{folderID}/update', [FolderController::class, 'updateFolder'])
        ->name('folder.update');

    // RUTA PARA ELIMINAR UNA CARPETA
    Route::delete('/project/{projectID}/folder/{folderID}/destroy', [FolderController::class, 'destroyFolder'])
        ->name('folder.destroy');

    // RUTA PARA ELIMINAR UNA CARPETA DENTRO DEL RECURSO COMPARTIDO
    Route::delete('/shared/folder/{folderID}/destroy', [FolderController::class, 'destroy'])
        ->name('shared.folder.destroy');

    // RUTA PARA ACTUALIZAR UNA CARPETA DENTRO DEL RECURSO COMPARTIDO
    Route::put('/folder/{folderID}/update', [FolderController::class, 'updateSharedFolder'])
        ->name('shared.folder.update');

    // RUTA PARA ELIMINAR UN DOCUMENTO
    Route::delete('/project/{projectID}/document/{documentID}/destroy', [DocumentController::class, 'destroy'])
        ->name('document.destroy');

    // RUTA PARA DESASOCIAR UN DOCUMENTO A UN RECURSO COMPARTIDO
    /*Route::delete('/shared/{folderID}/document/{documentID}/destroy', [DocumentController::class, 'disassociatedDocument'])
        ->name('shared.document.destroy');*/

    // RUTA PARA ACTUALIZAR UN DOCUMENTO
    Route::put('/project/{projectID}/document/{documentID}/update', [DocumentController::class, 'updateDocument'])
        ->name('document.update');

    //RUTA PARA ASOCIAR DOCUMENTOS AL RECURSO COMPARTIDO
    Route::post('/shared/{folderID}/document', [DocumentController::class, 'associatedDocument'])
        ->name('shared.document.associated');
});


Route::get('/user/edit', [RegisteredUserController::class, 'edit'])
    ->name('user.edit')->middleware('auth');

Route::post('/profile/update', [RegisteredUserController::class, 'updateProfile'])
    ->name('update.profile')->middleware('auth');


Route::post('/profile/password', [RegisteredUserController::class, 'changePassword'])
    ->name('update.password')->middleware('auth');



require __DIR__ . '/auth.php';
