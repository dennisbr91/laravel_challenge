<?php

namespace App\Http\Middleware;

use App\Models\Project;
use App\Models\Task;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedUser
{
    public function handle(Request $request, Closure $next)
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'No autenticado.'], 401);
            } else {
                return redirect('login');
            }
        }

        // Obtener el proyecto y la tarea desde la ruta
        $projectId = $request->route('project');
        $taskId = $request->route('task');

        if ($projectId) {
            $project = Project::findOrFail($projectId);

            // Verificar si el proyecto pertenece al usuario autenticado
            if (!$project->users->contains(Auth::id())) {
                return response()->json(['error' => 'No autorizado. Este proyecto no pertenece al usuario logueado'], 403);
            }

            // Si hay una tarea en la ruta, verificar si la tarea pertenece al proyecto y al usuario autenticado
            if ($taskId) {
                $task = Task::findOrFail($taskId);

                if ($task->project_id !== $project->id || !$project->users->contains(Auth::id())) {
                    return response()->json(['error' => 'No autorizado. Esta tarea no pertenece al proyecto o al usuario logueado'], 403);
                }
            }
        }

        return $next($request);
    }
}