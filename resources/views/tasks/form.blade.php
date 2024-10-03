<h1>{{ isset($task) ? 'Actualizar Tarea para el Proyecto: ' . $project->name : 'Crear Nueva Tarea para el Proyecto: ' . $project->name }}</h1>

<form action="{{ isset($task) ? route('projects.tasks.update', [$project->id, $task->id]) : route('projects.tasks.store', $project->id) }}" method="POST">
    @csrf
    @if(isset($task))
        @method('PUT')
    @endif
    <div>
        <label for="title">Título:</label>
        <input type="text" id="title" name="title" value="{{ old('title', $task->title ?? '') }}" required>
    </div>
    <div>
        <label for="description">Descripción:</label>
        <textarea id="description" name="description" required>{{ old('description', $task->description ?? '') }}</textarea>
    </div>
    <button type="submit">{{ isset($task) ? 'Actualizar Tarea' : 'Crear Tarea' }}</button>
</form>