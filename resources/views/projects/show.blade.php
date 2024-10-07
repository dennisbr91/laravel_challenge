@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ $project->name }}
    </h2>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $project->name }}</h1>
                <p class="mt-4 text-gray-700 dark:text-gray-300">{{ $project->description }}</p>
                <a href="{{ route('projects.tasks.create', ['project' => $project->id]) }}" class="inline-block mt-4 px-4 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-500 focus:bg-blue-700 active:bg-blue-900 transition ease-in-out duration-150">
                    Añadir Nueva Tarea
                </a>
                <ul class="mt-6 space-y-4">
                    @foreach($project->tasks as $task)
                        <li class="flex justify-between items-center">
                            <span class="text-gray-900 dark:text-gray-100">{{ $task->title }}</span>
                            <div class="space-x-2">
                                <a href="{{ route('projects.tasks.edit', ['project' => $project->id, 'task' => $task->id]) }}" class="text-blue-600 hover:underline">Editar</a>
                                <form action="{{ route('projects.tasks.destroy', ['project' => $project->id, 'task' => $task->id]) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Eliminar</button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
                <a href="{{ url()->previous() }}" class="mt-8 inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    Atrás
                </a>
                @if (session('success'))
                    <div class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-700" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection