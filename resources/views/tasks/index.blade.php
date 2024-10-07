@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h1 class="my-4">Tareas del Proyecto: {{ $project->name }}</h1>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <ul class="list-group mb-4">
                    @foreach ($tasks as $task)
                        <li class="list-group-item">
                            <h5>{{ $task->title }}</h5>
                            <p>{{ $task->description }}</p>
                        </li>
                    @endforeach
                </ul>

                <a href="{{ route('projects.tasks.create', $project->id) }}" class="btn btn-primary">Crear Nueva Tarea</a>
            </div>
        </div>
    </div>
@endsection