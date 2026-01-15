<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PsyControl')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @stack('styles')
</head>
<body>
    <!-- APENAS UMA NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="fas fa-brain me-2"></i>PsyControl
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    @auth
                        @if(Auth::user()->isAdmin())
                            <!-- Dashboard -->
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                                </a>
                            </li>

                            <!-- BOTÃO DE CRIAR USUÁRIO (APENAS ADMIN) -->
                            <li class="nav-item">
                                <a class="nav-link btn btn-success btn-sm mx-2" href="{{ route('users.create') }}">
                                    <i class="fas fa-user-plus me-1"></i> Criar Usuário
                                </a>
                            </li>

                            <!-- Gerenciar Usuários -->
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('users.index') }}">
                                    <i class="fas fa-users-cog me-1"></i> Gerenciar Usuários
                                </a>
                            </li>

                            <!-- Pacientes -->
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('patients.index') }}">
                                    <i class="fas fa-users me-1"></i> Pacientes
                                </a>
                            </li>

                            <!-- Consultas -->
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('appointments.index') }}">
                                    <i class="fas fa-calendar-alt me-1"></i> Consultas
                                </a>
                            </li>

                            <!-- Financeiro -->
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('financial.invoices.index') }}">
                                    <i class="fas fa-dollar-sign me-1"></i> Financeiro
                                </a>
                            </li>
                        @else
                            <!-- Conta do cliente: acesso apenas ao prontuário (Pacientes) -->
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('patients.index') }}">
                                    <i class="fas fa-users me-1"></i> Prontuário
                                </a>
                            </li>
                        @endif
                    @endauth
                </ul>
                
                <!-- Menu do Usuário -->
                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" 
                               role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i>
                                {{ Auth::user()->name }}
                                <small class="badge bg-secondary ms-1">
                                    {{ Auth::user()->type }}
                                </small>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.index') }}">
                                        <i class="fas fa-user me-2"></i> Meu Perfil
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-2"></i> Sair
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        <div class="container-fluid">
            <!-- Alertas -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Conteúdo Principal -->
            @yield('content')
        </div>
    </main>

    <footer class="footer mt-auto py-3 bg-light">
        <div class="container text-center">
            <span class="text-muted">
                <i class="fas fa-brain me-1"></i> PsyControl &copy; {{ date('Y') }} - Sistema de Gestão para Psicólogos
            </span>
        </div>
    </footer>

    <!-- Botão Flutuante -->
    @auth
    <div class="position-fixed" style="bottom: 20px; right: 20px; z-index: 1000;">
        <div class="d-flex flex-column">
            <a href="{{ route('users.create') }}" 
               class="btn btn-danger btn-lg rounded-circle p-3 mb-2 shadow" 
               style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;"
               title="Criar Novo Usuário">
                <i class="fas fa-user-plus"></i>
            </a>
            
            <a href="{{ route('users.index') }}" 
               class="btn btn-primary btn-lg rounded-circle p-3 shadow"
               style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;"
               title="Ver Todos Usuários">
                <i class="fas fa-users"></i>
            </a>
        </div>
    </div>
    @endauth

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>