<?php

use App\Http\Controllers\ProjectViewController;
use App\Http\Controllers\TaskViewController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'auth.user'])->group(function () {

    Route::get('/projects_list', [ProjectViewController::class, 'index'])->name('projects.list');
    Route::resource('projects', ProjectViewController::class);

    // Rutas para tareas dentro de proyectos
    Route::prefix('projects/{project}')->group(function () {
        Route::get('tasks/create', [TaskViewController::class, 'create'])->name('projects.tasks.create');
        Route::post('tasks', [TaskViewController::class, 'store'])->name('projects.tasks.store');
        Route::get('tasks', [TaskViewController::class, 'index'])->name('projects.tasks.index');
        Route::get('tasks/{task}/edit', [TaskViewController::class, 'edit'])->name('projects.tasks.edit');
        Route::put('tasks/{task}', [TaskViewController::class, 'update'])->name('projects.tasks.update');
        Route::delete('tasks/{task}', [TaskViewController::class, 'destroy'])->name('projects.tasks.destroy');
        Route::resource('tasks', TaskViewController::class)->except(['create', 'store', 'index', 'edit', 'destroy', 'update']);
    });
});



require __DIR__.'/auth.php';
