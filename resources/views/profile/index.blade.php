@extends('layouts.app')

@section('title', 'Meu Perfil')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-user me-1"></i> Meu Perfil
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Nome</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', Auth::user()->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', Auth::user()->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Telefone</label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', Auth::user()->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Endereço</label>
                            <textarea name="address" rows="2" class="form-control @error('address') is-invalid @enderror" placeholder="Rua, número, bairro, cidade">{{ old('address', Auth::user()->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Apenas profissionais/admins veem dados profissionais --}}
                        @if(Auth::user()->isProfessional() || Auth::user()->isAdmin())
                            <hr>
                            <h6 class="text-muted">Dados profissionais</h6>

                            <div class="mb-3">
                                <label class="form-label">CRP</label>
                                <input type="text" name="crp" class="form-control @error('crp') is-invalid @enderror" value="{{ old('crp', Auth::user()->crp) }}">
                                @error('crp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Especialidades (separadas por vírgula)</label>
                                <textarea name="specialties" rows="2" class="form-control @error('specialties') is-invalid @enderror" placeholder="Ex: TCC, Casal, Ansiedade">{{ old('specialties', is_array(Auth::user()->specialties) ? implode(', ', Auth::user()->specialties) : Auth::user()->specialties) }}</textarea>
                                @error('specialties')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                        <hr>
                        <h6 class="text-muted">Segurança</h6>

                        <div class="mb-3">
                            <label class="form-label">Nova senha</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Deixe em branco para não alterar">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirmar nova senha</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Repita a nova senha">
                        </div>

                        @php
                            $tipoPerfil = Auth::user()->type === 'assistant' ? 'cliente' : Auth::user()->type;
                        @endphp
                        <p class="text-muted small mb-3">
                            Tipo de usuário: <strong>{{ ucfirst($tipoPerfil) }}</strong>
                        </p>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Salvar alterações
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection