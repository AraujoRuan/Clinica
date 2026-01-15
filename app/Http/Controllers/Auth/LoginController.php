<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Mostrar formulário de login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Processar login
     */
    public function login(Request $request)
    {
        // Validação
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Debug: Verifique se o usuário existe
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return back()->withErrors([
                'email' => 'Usuário não encontrado.',
            ]);
        }

        // Debug: Verifique a senha
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'email' => 'Senha incorreta.',
            ]);
        }

        // Debug: Verifique se está ativo
        if (!$user->is_active) {
            return back()->withErrors([
                'email' => 'Usuário inativo.',
            ]);
        }

        // Tentar autenticação
        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
            'is_active' => true
        ], $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            // Log de login
            \Log::info('Login realizado', [
                'user_id' => Auth::id(),
                'email' => $request->email,
                'ip' => $request->ip()
            ]);
            
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Falha na autenticação.',
        ]);
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}