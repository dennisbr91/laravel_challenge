<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class TaskController extends Controller
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

    public function index($projectId)
    {
        $project = Project::findOrFail($projectId);
        return $project->tasks;
    }

    public function store(Request $request, $projectId)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        $project = Project::findOrFail($projectId);
        $task = $project->tasks()->create($request->all());
        return response()->json($task, 201);
    }

    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->update($request->all());
        return response()->json($task, 200);
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return response()->json(null, 204);
    }
}
