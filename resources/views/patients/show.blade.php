@extends('layouts.app')

@section('title', 'Prontuário do Paciente')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-3">
        <i class="fas fa-file-medical me-2"></i> Prontuário do Paciente #{{ $id ?? '' }}
    </h1>

    <div class="alert alert-info">
        Tela de prontuário do paciente (placeholder). Depois podemos mostrar dados, histórico de consultas, anotações, etc.
    </div>
</div>
@endsection