<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Rotas de autenticação
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Registro público de usuários (apenas profissionais)
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.store');

// Rotas protegidas
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Módulo de Usuários - Apenas para admins
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        
        // Lixeira
        Route::get('/trashed', [UserController::class, 'trashed'])->name('trashed');
        Route::post('/{id}/restore', [UserController::class, 'restore'])->name('restore');
    });
    
    // Módulo de Pacientes / Prontuário
    Route::prefix('patients')->name('patients.')->group(function () {
        Route::get('/', function () {
            $query = \App\Models\Patient::query();

            // Se não for admin, mostra apenas pacientes vinculados ao profissional logado
            if (!auth()->user()->isAdmin()) {
                $query->where('professional_id', auth()->id());
            }

            $search = request('q');
            if ($search) {
                // Busca apenas pelo código do prontuário
                $query->where('code', 'like', "%{$search}%");
            }

            // Filtro por data do prontuário (data de criação do paciente)
            $prontuarioDate = request('prontuario_date');
            if ($prontuarioDate) {
                $query->whereDate('created_at', $prontuarioDate);
            }

            // Filtro por data de consulta (appointments.start_time)
            $consultaDate = request('consulta_date');
            if ($consultaDate) {
                $query->whereHas('appointments', function ($q) use ($consultaDate) {
                    $q->whereDate('start_time', $consultaDate);
                });
            }

            $query->with('appointments');

            $patients = $query->orderBy('name')->paginate(15)->withQueryString();

            return view('patients.index', compact('patients', 'search', 'prontuarioDate', 'consultaDate'));
        })->name('index');
        
        Route::get('/create', function () {
            return view('patients.create');
        })->name('create');
        
        Route::get('/{id}', function ($id) {
            return view('patients.show', ['id' => $id]);
        })->name('show');
    });
    
    // Módulo de Consultas (placeholders - criar controllers depois)
    Route::prefix('appointments')->name('appointments.')->group(function () {
        Route::get('/', function () {
            return view('appointments.index');
        })->name('index');
        
        Route::get('/calendar', function () {
            return view('appointments.calendar');
        })->name('calendar');
        
        Route::get('/create', function () {
            return view('appointments.create');
        })->name('create');
    });
    
    // Módulo Financeiro (placeholders - criar controllers depois)
    Route::prefix('financial')->name('financial.')->group(function () {
        Route::prefix('invoices')->name('invoices.')->group(function () {
            Route::get('/', function () {
                return view('financial.invoices.index');
            })->name('index');
            
            Route::get('/create', function () {
                return view('financial.invoices.create');
            })->name('create');
            
            Route::get('/{id}', function ($id) {
                return view('financial.invoices.show', ['id' => $id]);
            })->name('show');
        });
        
        Route::prefix('expenses')->name('expenses.')->group(function () {
            Route::get('/', function () {
                return view('financial.expenses.index');
            })->name('index');
        });
        
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', function () {
                return view('financial.reports.index');
            })->name('index');
        });
    });
    
    // Perfil do usuário
    Route::get('/profile', function () {
        return view('profile.index');
    })->name('profile.index');

    Route::put('/profile', function (Request $request) {
        $user = $request->user();

        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone'       => ['nullable', 'string', 'max:255'],
            'address'     => ['nullable', 'string', 'max:1000'],
            'crp'         => ['nullable', 'string', 'max:255'],
            'specialties' => ['nullable', 'string', 'max:1000'],
            'password'    => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        // Campos básicos
        $user->name    = $data['name'];
        $user->email   = $data['email'];
        $user->phone   = $data['phone'] ?? null;
        $user->address = $data['address'] ?? null;

        // Se for profissional/admin, atualiza dados profissionais
        if ($user->isProfessional() || $user->isAdmin()) {
            $user->crp = $data['crp'] ?? null;

            if (!empty($data['specialties'])) {
                $specialtiesArray = array_filter(array_map('trim', explode(',', $data['specialties'])));
                $user->specialties = $specialtiesArray;
            } else {
                $user->specialties = [];
            }
        }

        // Atualiza a senha apenas se o usuário preencheu
        if (!empty($data['password'])) {
            $user->password = $data['password'];
        }

        $user->save();

        return back()->with('success', 'Perfil atualizado com sucesso.');
    })->name('profile.update');

    // Configurações (placeholder)
    Route::get('/settings', function () {
        return view('settings.index');
    })->name('settings.index');
});
