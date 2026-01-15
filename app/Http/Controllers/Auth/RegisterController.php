<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    /**
     * Mostrar formulário de registro
     */
    public function showRegistrationForm()
    {
        // Registro público: qualquer pessoa pode acessar o formulário
        return view('auth.register');
    }

    /**
     * Processar registro
     */
    public function register(Request $request)
    {
        // Validação do formulário de registro público
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['required', 'string', 'max:20'],
            'type' => ['required', 'in:professional,assistant'],
        ]);

        // Define o tipo conforme escolha do usuário (psicólogo ou cliente)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => $request->type,
            'crp' => null,
            'phone' => $request->phone,
            'is_active' => true,
        ]);

        // Opcional: logar automaticamente após registro
        // Auth::login($user);

        return redirect()->route('login')->with('success', 'Cadastro realizado com sucesso! Faça login para acessar o sistema.');
    }
}