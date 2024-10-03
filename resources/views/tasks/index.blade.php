
    <h1>Tareas del Proyecto: {{ $project->name }}</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <ul>
        @foreach ($tasks as $task)
            <li>{{ $task->title }} - {{ $task->description }}</li>
        @endforeach
    </ul>

    <a href="{{ route('projects.tasks.create', $project->id) }}">Crear Nueva Tarea</a>
