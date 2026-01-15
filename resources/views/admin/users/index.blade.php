@extends('layouts.app')

@section('title', 'Gerenciar Usuários - PsyControl')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">Gerenciar Usuários</h1>
                <a href="{{ route('users.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Novo Usuário
                </a>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Lista de Usuários</h6>
        </div>
        <div class="card-body">
            <!-- Filtro de Busca -->
            <form method="GET" action="{{ route('users.index') }}" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Buscar por nome ou email..." 
                           value="{{ request('search') }}">
                    <button class="btn btn-outline-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                    @if(request('search'))
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </div>
            </form>

            <!-- Tabela de Usuários -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Tipo</th>
                            <th>Status</th>
                            <th>Telefone</th>
                            <th>CRP</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-3">
                                        <span class="initials">
                                            {{ substr($user->name, 0, 1) }}
                                        </span>
                                    </div>
                                    <div>
                                        <strong>{{ $user->name }}</strong>
                                        @if($user->id === auth()->id())
                                            <span class="badge bg-info ms-2">Você</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @switch($user->type)
                                    @case('admin')
                                        <span class="badge bg-danger">Administrador</span>
                                        @break
                                    @case('professional')
                                        <span class="badge bg-primary">Profissional</span>
                                        @break
                                    @case('assistant')
                                        <span class="badge bg-secondary">Assistente</span>
                                        @break
                                @endswitch
                            </td>
                            <td>
                                @if($user->is_active)
                                    <span class="badge bg-success">Ativo</span>
                                @else
                                    <span class="badge bg-danger">Inativo</span>
                                @endif
                            </td>
                            <td>{{ $user->phone }}</td>
                            <td>{{ $user->crp ?? 'N/A' }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('users.show', $user) }}" 
                                       class="btn btn-sm btn-info" title="Ver detalhes">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('users.edit', $user) }}" 
                                       class="btn btn-sm btn-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('users.destroy', $user) }}" 
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Tem certeza que deseja excluir este usuário?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Excluir">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-users fa-2x text-muted mb-3"></i>
                                <p class="text-muted">Nenhum usuário encontrado.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            @if($users->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $users->links() }}
                </div>
            @endif

            <!-- Link para lixeira -->
            <div class="mt-4">
                <a href="{{ route('users.trashed') }}" class="text-decoration-none">
                    <i class="fas fa-trash-alt me-2"></i>Ver usuários excluídos
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.avatar-circle {
    width: 40px;
    height: 40px;
    background-color: #4e73df;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
}
.initials {
    font-size: 16px;
}
.table-hover tbody tr:hover {
    background-color: rgba(0,0,0,.02);
}
</style>
@endpush