<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $projectId = $request->route('project');
            if ($projectId) {
                $project = Project::findOrFail($projectId);

                if (!$project->users->contains(Auth::id())) {
                    return response()->json(['error' => 'No autorizado.'], 403);
                }
            }

            return $next($request);
        });
    }

    public function index()
    {
        return Project::whereHas('users', function($query) {
            $query->where('user_id', Auth::id());
        })->get();
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

        return response()->json($project, 201);
    }

    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        if ($project->user_id !== Auth::id() && !$project->users->contains(Auth::id())) {
            return response()->json(['error' => 'No autorizado.'], 403);
        }

        $request->validate([
            'name' => 'required|unique:projects,name,' . $id . ',id,user_id,' . Auth::id(),
            'description' => 'required',
            'due_date' => 'required|date',
        ]);

        $project->update($request->all());

        return response()->json($project);
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);

        if ($project->user_id !== Auth::id() && !$project->users->contains(Auth::id())) {
            return response()->json(['error' => 'No autorizado.'], 403);
        }

        $project->tasks()->delete();
        $project->delete();

        return response()->json(null, 204);
    }
}


