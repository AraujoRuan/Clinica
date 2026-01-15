<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:manage-users')->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    }

    /**
     * Listar todos os usuários
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $users = User::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
        })
        ->orderBy('name')
        ->paginate(20);

        return view('admin.users.index', compact('users', 'search'));
    }

    /**
     * Mostrar formulário de criação
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Armazenar novo usuário
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'type' => ['required', 'in:admin,professional,assistant'],
            'crp' => ['nullable', 'string', 'max:20'],
            'phone' => ['required', 'string', 'max:20'],
            'specialties' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => $request->type,
            'crp' => $request->crp,
            'phone' => $request->phone,
            'specialties' => $request->specialties,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Usuário criado com sucesso!');
    }

    /**
     * Mostrar detalhes do usuário
     */
    public function show(User $user)
    {
        $user->loadCount(['patients', 'appointments', 'invoices']);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Mostrar formulário de edição
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Atualizar usuário
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'type' => ['required', 'in:admin,professional,assistant'],
            'crp' => ['nullable', 'string', 'max:20'],
            'phone' => ['required', 'string', 'max:20'],
            'specialties' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ];

        // Apenas validar senha se for fornecida
        if ($request->filled('password')) {
            $rules['password'] = ['confirmed', Rules\Password::defaults()];
        }

        $request->validate($rules);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'type' => $request->type,
            'crp' => $request->crp,
            'phone' => $request->phone,
            'specialties' => $request->specialties,
            'is_active' => $request->boolean('is_active'),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', 'Usuário atualizado com sucesso!');
    }

    /**
     * Excluir usuário (soft delete)
     */
    public function destroy(User $user)
    {
        // Não permitir excluir a si mesmo
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'Você não pode excluir sua própria conta!');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Usuário excluído com sucesso!');
    }

    /**
     * Restaurar usuário excluído
     */
    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->route('users.index')
            ->with('success', 'Usuário restaurado com sucesso!');
    }

    /**
     * Listar usuários excluídos
     */
    public function trashed()
    {
        $users = User::onlyTrashed()->orderBy('deleted_at', 'desc')->paginate(20);
        return view('admin.users.trashed', compact('users'));
    }
}