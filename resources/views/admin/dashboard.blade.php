@extends('layouts.app')

@section('title', 'Dashboard - PsyControl')

@section('content')
<div class="container-fluid">
    <!-- Botões de Ação Rápida -->
    @if(auth()->user()->isAdmin())
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Ações Rápidas</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('users.create') }}" class="btn btn-primary w-100 py-3">
                                <i class="fas fa-user-plus fa-2x mb-2"></i><br>
                                Novo Usuário
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('patients.create') }}" class="btn btn-success w-100 py-3">
                                <i class="fas fa-user-plus fa-2x mb-2"></i><br>
                                Novo Paciente
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('appointments.create') }}" class="btn btn-info w-100 py-3">
                                <i class="fas fa-calendar-plus fa-2x mb-2"></i><br>
                                Nova Consulta
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('financial.invoices.create') }}" class="btn btn-warning w-100 py-3">
                                <i class="fas fa-file-invoice-dollar fa-2x mb-2"></i><br>
                                Nova Fatura
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Resto do dashboard... -->
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4">Dashboard</h1>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <!-- ... código existente do dashboard ... -->
</div>
@endsection