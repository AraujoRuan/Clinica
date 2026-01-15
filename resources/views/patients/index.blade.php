@extends('layouts.app')

@section('title', 'Prontuário - Pacientes')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">
            <i class="fas fa-users me-2"></i> Prontuários
        </h1>
        @if(Auth::user() && Auth::user()->isAdmin())
            <a href="{{ route('patients.create') }}" class="btn btn-primary">
                <i class="fas fa-user-plus me-1"></i> Novo Paciente
            </a>
        @endif
    </div>

    {{-- Filtro de busca --}}
    <form method="GET" action="{{ route('patients.index') }}" class="mb-3">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label mb-1">Buscar prontuário</label>
                <input type="text" name="q" class="form-control" placeholder="Código do prontuário" value="{{ $search ?? request('q') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label mb-1">Data do prontuário</label>
                <input type="date" name="prontuario_date" class="form-control" value="{{ $prontuarioDate ?? request('prontuario_date') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label mb-1">Data da consulta</label>
                <input type="date" name="consulta_date" class="form-control" value="{{ $consultaDate ?? request('consulta_date') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100 mb-1">
                    <i class="fas fa-search me-1"></i> Buscar
                </button>
                @if(request('q') || request('prontuario_date') || request('consulta_date'))
                    <a href="{{ route('patients.index') }}" class="btn btn-link p-0">Limpar</a>
                @endif
            </div>
        </div>
    </form>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Data do prontuário</th>
                        <th>Data da última consulta</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($patients as $patient)
                        @php
                            $lastAppointment = $patient->appointments->sortByDesc('start_time')->first();
                        @endphp
                        <tr>
                            <td>{{ optional($patient->created_at)->format('d/m/Y') }}</td>
                            <td>
                                @if($lastAppointment)
                                    {{ \Carbon\Carbon::parse($lastAppointment->start_time)->format('d/m/Y') }}
                                @else
                                    <span class="text-muted">Sem consultas</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('patients.show', $patient->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-file-medical me-1"></i> Ver prontuário
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">
                                Nenhum prontuário encontrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                </table>
            </div>

            @if($patients->hasPages())
                <div class="p-3">
                    {{ $patients->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection