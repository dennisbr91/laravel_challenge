<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index()
    {

        $projects = Project::whereHas('users', function($query) {
            $query->where('user_id', Auth::id());
        })->get();

        return response()->json($projects, 201);
    }

    public function store(Request $request)
    {
        try {
            // Validar los campos
            $request->validate([
                'name' => 'required|unique:projects,name',
                'description' => 'required',
                'due_date' => 'required|date',
            ]);

            // Crear un nuevo proyecto
            $project = new Project($request->all());
            $project->save();

            // Agregar el ID del proyecto y el ID del usuario en la tabla pivote
            $project->users()->attach(Auth::id());

            return response()->json($project, 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Capturar la excepción de validación y retornar los errores en formato JSON
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            // Capturar cualquier otra excepción y retornar un error genérico
            return response()->json(['error' => 'Error al crear el proyecto.'], 500);
        }
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

        return response()->json($project);
    }

    public function destroy($id)
    {
        try {
            // Validar que el ID esté presente y sea válido
            if (!$id) {
                return response()->json(['error' => 'ID no proporcionado.'], 400);
            }

            // Buscar el proyecto por ID
            $project = Project::findOrFail($id);

            // Verificar la autorización del usuario
            if (!$project->users->contains(Auth::id())) {
                return response()->json(['error' => 'No autorizado.'], 403);
            }

            // Eliminar las tareas asociadas y el proyecto
            $project->tasks()->delete();
            $project->delete();

            return response()->json(null, 204);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Capturar la excepción si el proyecto no se encuentra
            return response()->json(['error' => 'Proyecto no encontrado.'], 404);

        } catch (\Exception $e) {
            // Capturar cualquier otra excepción y retornar un error genérico
            return response()->json(['error' => 'Error al eliminar el proyecto.'], 500);
        }
    }
}


