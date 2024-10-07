@extends('layouts.app')

@section('title', 'Lista de Proyectos')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Mis Proyectos
    </h2>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="mb-4">
                    <a href="{{ route('projects.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Crear Nuevo Proyecto</a>
                </div>
                <ul class="list-disc pl-5">
                    @foreach($projects as $project)
                        <li class="mb-4">
                            <div class="flex justify-between items-center">
                                <div>
                                    <a href="{{ route('projects.show', $project->id) }}" class="text-blue-500 hover:underline">{{ $project->name }}</a>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ route('projects.edit', $project->id) }}" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-700">Editar</a>
                                    <form action="{{ route('projects.destroy', $project->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-700">Eliminar</button>
                                    </form>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
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