<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class ProjectViewController extends Controller
{
    protected $projects;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->projects = Project::whereHas('users', function($query) {
                $query->where('user_id', Auth::id());
            })->get();

            // Compartir la variable con todas las vistas
            View::share('projects', $this->projects);

            return $next($request);
        });
    }

    public function index()
    {
        return view('projects.index');
    }

    public function create()
    {
        return view('projects.form');
    }

    public function edit($id)
    {
        $project = Project::findOrFail($id);
        return view('projects.form', compact('project'));
    }

    public function show($id)
    {
        $project = Project::findOrFail($id);
        return view('projects.show', compact('project'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:projects,name',
            'description' => 'required',
            'due_date' => 'required|date',
        ]);

        $project = new Project($request->all());
        $project->save();

        // Agregar el ID del proyecto y el ID del usuario en la tabla pivote
        $project->users()->attach(Auth::id());

        return redirect()->route('projects.list')->with('success', 'Proyecto creado exitosamente.');
    }

    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        // Verificar si el usuario autenticado está relacionado con el proyecto
        if (!$project->users->contains(Auth::id())) {
            return redirect()->route('projects.list')->with('error', 'No autorizado.');
        }

        $request->validate([
            'name' => 'required|unique:projects,name,' . $id,
            'description' => 'required',
            'due_date' => 'required|date',
        ]);

        $project->update($request->all());

        return redirect()->route('projects.list')->with('success', 'Proyecto actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);

        if ($project->user_id !== Auth::id() && !$project->users->contains(Auth::id())) {
            return redirect()->route('projects.index')->with('error', 'No autorizado.');
        }

        $project->tasks()->delete();
        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Proyecto eliminado exitosamente.');
    }
}