<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Listar todos os usuários
     */
    public function index(Request $request)
    {
        // Verificar autenticação
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Verificar se é admin (opcional - remova se quiser acesso livre)
        // if (!auth()->user()->isAdmin()) {
        //     abort(403, 'Acesso restrito a administradores.');
        // }

        $search = $request->get('search');
        
        $users = User::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
        })
        ->orderBy('name')
        ->paginate(10);

        return view('admin.users.index', compact('users', 'search'));
    }

    /**
     * Mostrar formulário de criação
     */
    public function create()
    {
        // Verificar autenticação
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        return view('admin.users.create');
    }

    /**
     * Armazenar novo usuário
     */
    public function store(Request $request)
    {
        // Verificar autenticação
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'type' => 'required|in:admin,professional,assistant',
            'phone' => 'required|string|max:20',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => $request->type,
            'phone' => $request->phone,
            'is_active' => true,
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Usuário criado com sucesso!');
    }

    /**
     * Mostrar detalhes do usuário
     */
    public function show(User $user)
    {
        // Verificar autenticação
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        return view('admin.users.show', compact('user'));
    }

    /**
     * Mostrar formulário de edição
     */
    public function edit(User $user)
    {
        // Verificar autenticação
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Atualizar usuário
     */
    public function update(Request $request, User $user)
    {
        // Verificar autenticação
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'type' => 'required|in:admin,professional,assistant',
            'phone' => 'required|string|max:20',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'type' => $request->type,
            'phone' => $request->phone,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', 'Usuário atualizado com sucesso!');
    }

    /**
     * Excluir usuário
     */
    public function destroy(User $user)
    {
        // Verificar autenticação
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Não permitir excluir a si mesmo
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'Você não pode excluir sua própria conta!');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Usuário excluído com sucesso!');
    }
}