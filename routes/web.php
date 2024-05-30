<?php
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CollaboratorController;
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

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });


// RUTA PAGINA DE INICIO
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// RUTA LOGIN (INICIAL)
Route::get('/', [AuthenticatedSessionController::class, 'create']);

// RUTA DE LAS VIGENCIAS
Route::get('/validity', [ValidityController::class, "index"])->name('validity.index');

// RUTA PARA MOSTRAR LOS PROYECTOS DE UNA VIGENCIA
Route::get('/{validityYear}/projects', [ValidityController::class, "projects"])->name('validity.projects');


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
    Route::post('/investigator/store', [InvestigatorController::class,'store'])
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

    // RUTA PARA MOSTRAR LOS COLABORADORES
    Route::get('/collaborator/index', [CollaboratorController::class, 'index'])
        ->name('collaborator.index');

    // RUTA PARA CREAR UN COLABORADOR
    Route::post('/collaborator/store', [CollaboratorController::class,'store'])
        ->name('collaborator.store');

    // RUTA PARA ELIMINAR UN COLABORADOR
    Route::delete('/collaborator/{userID}/destroy', [CollaboratorController::class, 'destroy'])
        ->name('collaborator.destroy');
});

require __DIR__.'/auth.php';
