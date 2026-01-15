@extends('layouts.app')

@section('title', 'Editar Usuário - PsyControl')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Usuários</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Editar Usuário</li>
                </ol>
            </nav>
            
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">Editar Usuário: {{ $user->name }}</h1>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Voltar
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-warning">
                    <h6 class="m-0 font-weight-bold">Editar Informações do Usuário</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('users.update', $user) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Nome -->
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nome Completo *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Senha (opcional) -->
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Deixe os campos de senha em branco se não quiser alterar a senha.
                        </div>

                        <div class="row">
                            <!-- Nova Senha -->
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Nova Senha</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password">
                                <small class="text-muted">Deixe em branco para manter a senha atual</small>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Confirmar Nova Senha -->
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Confirmar Nova Senha</label>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>

                        <div class="row">
                            <!-- Tipo de Usuário -->
                            <div class="col-md-4 mb-3">
                                <label for="type" class="form-label">Tipo de Usuário *</label>
                                <select class="form-select @error('type') is-invalid @enderror" 
                                        id="type" name="type" required>
                                    <option value="admin" {{ old('type', $user->type) == 'admin' ? 'selected' : '' }}>
                                        Administrador
                                    </option>
                                    <option value="professional" {{ old('type', $user->type) == 'professional' ? 'selected' : '' }}>
                                        Profissional
                                    </option>
                                    <option value="assistant" {{ old('type', $user->type) == 'assistant' ? 'selected' : '' }}>
                                        Assistente
                                    </option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Telefone -->
                            <div class="col-md-4 mb-3">
                                <label for="phone" class="form-label">Telefone *</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', $user->phone) }}" 
                                       placeholder="(11) 99999-9999" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- CRP (apenas para profissionais) -->
                            <div class="col-md-4 mb-3" id="crp-field">
                                <label for="crp" class="form-label">CRP</label>
                                <input type="text" class="form-control @error('crp') is-invalid @enderror" 
                                       id="crp" name="crp" value="{{ old('crp', $user->crp) }}"
                                       placeholder="00/000000">
                                <small class="text-muted">Apenas para profissionais</small>
                                @error('crp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Especialidades -->
                        <div class="mb-3">
                            <label for="specialties" class="form-label">Especialidades</label>
                            <textarea class="form-control @error('specialties') is-invalid @enderror" 
                                      id="specialties" name="specialties" rows="2"
                                      placeholder="Ex: Psicologia Clínica, TCC, Psicanálise">{{ old('specialties', $user->specialties) }}</textarea>
                            @error('specialties')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" 
                                       id="is_active" name="is_active" value="1" 
                                       {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Usuário Ativo
                                </label>
                            </div>
                            <small class="text-muted">
                                Usuários inativos não conseguem fazer login no sistema.
                            </small>
                        </div>

                        <!-- Botões -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                            <div>
                                <a href="{{ route('users.show', $user) }}" class="btn btn-info me-2">
                                    <i class="fas fa-eye me-2"></i>Ver Detalhes
                                </a>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save me-2"></i>Atualizar Usuário
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Estatísticas do usuário -->
            <div class="card shadow mt-4">
                <div class="card-header bg-light">
                    <h6 class="m-0 font-weight-bold">Estatísticas do Usuário</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="card border-primary">
                                <div class="card-body">
                                    <h3 class="text-primary">{{ $user->patients_count ?? 0 }}</h3>
                                    <p class="small text-muted mb-0">Pacientes</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-success">
                                <div class="card-body">
                                    <h3 class="text-success">{{ $user->appointments_count ?? 0 }}</h3>
                                    <p class="small text-muted mb-0">Consultas</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-info">
                                <div class="card-body">
                                    <h3 class="text-info">{{ $user->invoices_count ?? 0 }}</h3>
                                    <p class="small text-muted mb-0">Faturas</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-secondary">
                                <div class="card-body">
                                    <h5 class="text-secondary">
                                        {{ $user->created_at->format('d/m/Y') }}
                                    </h5>
                                    <p class="small text-muted mb-0">Cadastrado em</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Máscara para telefone
    const phoneInput = document.getElementById('phone');
    phoneInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 10) {
            value = value.replace(/^(\d{2})(\d{5})(\d{4}).*/, '($1) $2-$3');
        } else if (value.length > 6) {
            value = value.replace(/^(\d{2})(\d{4})(\d{0,4}).*/, '($1) $2-$3');
        } else if (value.length > 2) {
            value = value.replace(/^(\d{2})(\d{0,5})/, '($1) $2');
        } else if (value.length > 0) {
            value = value.replace(/^(\d*)/, '($1');
        }
        e.target.value = value;
    });

    // Mostrar/ocultar CRP baseado no tipo
    const typeSelect = document.getElementById('type');
    const crpField = document.getElementById('crp-field');
    
    function toggleCrpField() {
        if (typeSelect.value === 'professional') {
            crpField.style.display = 'block';
        } else {
            crpField.style.display = 'none';
        }
    }
    
    typeSelect.addEventListener('change', toggleCrpField);
    toggleCrpField(); // Executar na carga inicial
});
</script>
@endpush