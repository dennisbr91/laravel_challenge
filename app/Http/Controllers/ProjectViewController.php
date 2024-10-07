<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectViewController extends Controller
{


    public function index()
    {
        $projects = Project::whereHas('users', function($query) {
        $query->where('user_id', Auth::id());
    })->get();

        return view('projects.index', compact('projects'));
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

        return redirect()->route('projects.index')->with('success', 'Proyecto creado exitosamente.');
    }

    public function update(Request $request, $id)
    {
        // Buscar el proyecto por ID
        $project = Project::findOrFail($id);


        // Validar los campos
        $request->validate([
            'name' => [
                'required',
                function ($attribute, $value, $fail) use ($id) {
                    $userId = Auth::id();
                    $exists = Project::where('name', $value)
                        ->whereHas('users', function ($query) use ($userId) {
                            $query->where('users.id', $userId); // Especificar la tabla 'users'
                        })
                        ->where('projects.id', '!=', $id) // Especificar la tabla 'projects'
                        ->exists();

                    if ($exists) {
                        $fail('El nombre del proyecto ya está en uso para este usuario.');
                    }
                },
            ],
            'description' => 'required',
            'due_date' => 'required|date',
        ]);

        // Actualizar el proyecto
        $project->update($request->all());

        return redirect()->route('projects.index')->with('success', 'Proyecto actualizado exitosamente.');
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