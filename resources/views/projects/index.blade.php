<h1>Mis Proyectos</h1>
<a href="{{ route('projects.create') }}">Crear Nuevo Proyecto</a>
<a href="{{ route('logout') }}"
   onclick="event.preventDefault();
             document.getElementById('logout-form').submit();">
    Cerrar Sessión
</a>
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
<ul>
    @foreach($projects as $project)
        <li>
            <a href="{{ route('projects.show', $project->id) }}">{{ $project->name }}</a>
            <a href="{{ route('projects.edit', $project->id) }}">Editar</a>
            <form action="{{ route('projects.destroy', $project->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit">Eliminar</button>
            </form>
        </li>
    @endforeach
</ul>