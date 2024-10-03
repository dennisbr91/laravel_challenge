<h1>{{ $project->name }}</h1>
<p>{{ $project->description }}</p>
<a href="{{ route('projects.tasks.create', ['project' => $project->id]) }}">Añadir Nueva Tarea</a>
<ul>
    @foreach($project->tasks as $task)
        <li>
            {{ $task->title }}
            <a href="{{ route('projects.tasks.edit', ['project' => $project->id, 'task' => $task->id]) }}">Editar</a>
            <form action="{{ route('projects.tasks.destroy', ['project' => $project->id, 'task' => $task->id]) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit">Eliminar</button>
            </form>
        </li>
    @endforeach
</ul>