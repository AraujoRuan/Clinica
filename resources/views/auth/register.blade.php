<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - PsyControl</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .register-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            padding: 40px;
            width: 100%;
            max-width: 450px;
        }
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo i {
            font-size: 3rem;
            color: #667eea;
            margin-bottom: 10px;
        }
        .logo h1 {
            color: #333;
            font-size: 1.8rem;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="register-card">
        <div class="logo">
            <i class="fas fa-user-plus"></i>
            <h1>Criar conta</h1>
            <p class="text-muted">Cadastro de profissional no PsyControl</p>
        </div>

        <form method="POST" action="{{ route('register.store') }}">
            @csrf

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Ops!</strong> Verifique os campos abaixo.
                </div>
            @endif

            <div class="mb-3">
                <label for="name" class="form-label">Nome completo</label>
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autofocus>
                @error('name')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Senha</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                @error('password')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirmar senha</label>
                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required>
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Telefone</label>
                <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required>
                @error('phone')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="type" class="form-label">Tipo de usuário</label>
                <select id="type" name="type" class="form-select @error('type') is-invalid @enderror" required>
                    <option value="">Selecione uma opção</option>
                    <option value="professional" {{ old('type') == 'professional' ? 'selected' : '' }}>Psicólogo(a)</option>
                    <option value="assistant" {{ old('type') == 'assistant' ? 'selected' : '' }}>Cliente</option>
                </select>
                @error('type')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="d-grid gap-2 mb-3">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-check me-2"></i> Finalizar cadastro
                </button>
            </div>

            <div class="text-center">
                <a href="{{ route('login') }}" class="text-muted">Já tem conta? Fazer login</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
