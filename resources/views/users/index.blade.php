@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/css/responsive-tables.css') }}">
<div class="row">
    @if(auth()->user() && auth()->user()->is_admin)
    <div class="col-md-12">
        <div class="card animate__animated animate__fadeIn">
            <div class="card-body">
                <h2 class="mb-0 text-center">Lista de Usuarios</h2>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <button style="background-color: #1061d2; color: #ffffff;" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
                        AGREGAR
                    </button>
                </div>
                <div class="table-responsive table-container animate__animated animate__fadeIn">
                    <table class="table table-bordered table-hover align-middle table-compact">
                        <thead class="table-dark text-white">
                            <tr>
                                <th scope="col">Nombre</th>
                                <th scope="col">Correo Electrónico</th>
                                <th scope="col">Es Admin</th>
                                @if(auth()->user()->email === 'adrianagm291104@gmail.com')
                                <th scope="col">Acciones</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                            <tr>
                                <td data-label="ID">
                                    {{ $user->name }}
                                    @if($user->avatar)
                                        <img src="{{ $user->avatar }}" alt="Avatar" class="user-avatar-small ms-2">
                                    @endif
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge {{ $user->is_admin ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $user->is_admin ? 'Administrador' : 'Usuario' }}
                                    </span>
                                    @if($user->google_id)
                                        <small class="text-muted d-block">Cuenta Google</small>
                                    @endif
                                </td>
                                @if(auth()->user()->email === 'adrianagm291104@gmail.com')
                                <td>
                                    @if($user->id !== auth()->user()->id)
                                        <form action="{{ route('users.toggle-admin', $user->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm {{ $user->is_admin ? 'btn-warning' : 'btn-success' }}" 
                                                    onclick="return confirm('¿Estás seguro de {{ $user->is_admin ? 'remover los permisos de administrador' : 'promover como administrador' }} a {{ $user->name }}?')">
                                                {{ $user->is_admin ? 'Remover Admin' : 'Hacer Admin' }}
                                            </button>
                                        </form>
                                    @else
                                        <span class="badge bg-primary">Tú</span>
                                    @endif
                                </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @include('users.create')
            </div>
        </div>
    </div>
</div>
@endif

<style>
.user-avatar-small {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    object-fit: cover;
    border: 1px solid #dee2e6;
}

.badge {
    font-size: 0.75rem;
}

.table td {
    vertical-align: middle;
}
</style>

@endsection