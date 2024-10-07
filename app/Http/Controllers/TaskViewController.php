<?php
namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TaskViewController extends Controller
{

    public function index($projectId)
    {
        $project = Project::findOrFail($projectId);
        $tasks = $project->tasks;
        return view('tasks.index', compact('tasks', 'project'));
    }

    public function edit($projectId, $taskId)
    {
        $task = Task::findOrFail($taskId);
        $project = Project::findOrFail($projectId);

        // Verificar si la tarea pertenece al proyecto
        if ($task->project_id !== $project->id) {
            // Mostrar un error si la tarea no pertenece al proyecto
            return redirect()->back()->withErrors(['error' => 'La tarea no pertenece a este proyecto.']);
        }

        return view('tasks.form', compact('task', 'project'));
    }

    public function show($id)
    {
        $project = Project::findOrFail($id);
        return view('projects.show', compact('project'));
    }

    public function create($projectId)
    {
        $project = Project::findOrFail($projectId);
        $tasks = $project->tasks;
        return view('tasks.form', compact('tasks', 'project'));
    }

    public function store(Request $request, $projectId)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        $project = Project::findOrFail($projectId);
        $task = $project->tasks()->create($request->all());
        $tasks = $project->tasks;

        return view('projects.show', compact('tasks', 'project'))->with('success', 'Tarea creada exitosamente.');
    }

    public function update(Request $request, $projectId, $taskId)
    {

        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        $project = Project::findOrFail($projectId);
        $task = Task::findOrFail($taskId);

        $task->title = $request->title;
        $task->description = $request->description;
        $task->save();

        return view('projects.show', compact('task', 'project'))->with('success', 'Tarea Modificada exitosamente.');
    }

    public function destroy($projectId, $taskId)
    {
        $task = Task::findOrFail($taskId);
        $project = Project::findOrFail($projectId);

        $task->delete();

        return view('projects.show', compact('task', 'project'))->with('success', 'Tarea eliminada exitosamente.');
    }
}