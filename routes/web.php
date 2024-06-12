<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CollaboratorController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\InvestigatorController;
use App\Http\Controllers\ProjectController;
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
    ->name('document.upload')->middleware("protect.project");


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
});


Route::get('/user/edit', [RegisteredUserController::class, 'edit'])
    ->name('user.edit')->middleware('auth');

Route::post('/profile/update', [RegisteredUserController::class, 'updateProfile'])
    ->name('update.profile')->middleware('auth');

require __DIR__ . '/auth.php';
