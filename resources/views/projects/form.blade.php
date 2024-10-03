<form action="{{ isset($project) ? route('projects.update', $project->id) : route('projects.store') }}" method="POST">
    @csrf
    @if(isset($project))
        @method('PUT')
    @endif
    <div>
        <label for="name">Nombre del Proyecto:</label>
        <input type="text" id="name" name="name" value="{{ old('name', $project->name ?? '') }}" required>
    </div>
    <div>
        <label for="description">Descripción:</label>
        <textarea id="description" name="description" required>{{ old('description', $project->description ?? '') }}</textarea>
    </div>
    <div>
        <label for="due_date">Fecha de Vencimiento:</label>
        <input type="date" id="due_date" name="due_date" value="{{ old('due_date', $project->due_date ?? '') }}" required>
    </div>
    <button type="submit">{{ isset($project) ? 'Actualizar' : 'Crear' }}</button>
</form>