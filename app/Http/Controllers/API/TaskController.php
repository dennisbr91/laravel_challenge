<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TaskController extends Controller
{
//    public function __construct()
//    {
//        $this->middleware(function ($request, $next) {
//            $projectId = $request->route('project');
//            if ($projectId) {
//                $project = Project::findOrFail($projectId);
//
//                if (!$project->users->contains(Auth::id())) {
//                    return response()->json(['error' => 'No autorizado.'], 403);
//                }
//            }
//
//            return $next($request);
//        });
//    }

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
            'completed' => 'required|boolean',
        ]);

        $project = Project::findOrFail($projectId);
        $task = $project->tasks()->create($request->all());

        return response()->json($task, 201);
    }

    public function update(Request $request, $projectId, $taskId)
    {
        try {
            // Validar los campos
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'completed' => 'required|boolean',
            ]);

            // Buscar la tarea por ID
            $task = Task::findOrFail($taskId);

            // Actualizar la tarea
            $task->title = $request->title;
            $task->description = $request->description;
            $task->completed = $request->completed;
            $task->save();

            return response()->json($task, 200);

        } catch (ValidationException $e) {
            // Capturar la excepción de validación y retornar los errores en formato JSON
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);

        } catch (ModelNotFoundException $e) {
            // Capturar la excepción si la tarea o el proyecto no se encuentran
            return response()->json(['error' => 'Tarea o proyecto no encontrado.'], 404);

        } catch (\Exception $e) {
            // Capturar cualquier otra excepción y retornar un error genérico
            return response()->json(['error' => 'Error al actualizar la tarea.'], 500);
        }
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return response()->json(null, 204);
    }
}
