<?php

namespace App\Http\Middleware;

use App\Models\Project;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedUser
{
    public function handle(Request $request, Closure $next)
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            return redirect('login');
        }

        // Obtener el proyecto y verificar si el usuario está autorizado
        $projectId = $request->route('project');
        if ($projectId) {
            $project = Project::findOrFail($projectId);

            if (!$project->users->contains(Auth::id())) {
                return response()->json(['error' => 'No autorizado.'], 403);
            }
        }

        return $next($request);
    }
}