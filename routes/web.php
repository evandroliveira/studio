<?php

use App\Http\Controllers\AgendamentoController;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\ServicoController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

Route::get('/', function () {
    return redirect('/agendamento');
});

Route::get('/agendamento', function () {
    return response('<h1>Agendamento</h1>', 200);
});

Route::get('/agendamento/horarios-disponiveis', function () {
    return response()->json([]);
});

Route::get('/esqueci-a-senha', function () {
    return view('forgot-password');
})->middleware('guest')->name('password.request');

Route::post('/esqueci-a-senha', function (Request $request) {
    $request->validate([
        'email' => ['required', 'email'],
    ]);

    $status = Password::sendResetLink($request->only('email'));

    if ($status === Password::RESET_LINK_SENT) {
        return back()->with('status', 'Enviamos as instrucoes de redefinicao para o e-mail informado.');
    }

    return back()
        ->withInput()
        ->withErrors(['email' => 'Nao foi possivel enviar o link de redefinicao para este e-mail.']);
})->middleware('guest')->name('password.email');

Route::get('/redefinir-senha/{token}', function (string $token, Request $request) {
    return view('reset-password', [
        'token' => $token,
        'email' => $request->string('email')->toString(),
    ]);
})->middleware('guest')->name('password.reset');

Route::post('/redefinir-senha', function (Request $request) {
    $request->validate([
        'token' => ['required'],
        'email' => ['required', 'email'],
        'password' => ['required', 'confirmed', 'min:8'],
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function (User $user, string $password) {
            $user->forceFill([
                'password' => Hash::make($password),
                'remember_token' => Str::random(60),
            ])->save();
        }
    );

    if ($status === Password::PASSWORD_RESET) {
        return redirect()->route('login')->with('status', 'Senha redefinida com sucesso. Entre com a nova senha.');
    }

    return back()
        ->withInput($request->except('password', 'password_confirmation'))
        ->withErrors(['email' => 'Nao foi possivel redefinir a senha com os dados informados.']);
})->middleware('guest')->name('password.update');

Route::get('/cadastro', function () {
    return view('register');
})->middleware('guest')->name('register');

Route::post('/cadastro', function (Request $request) {
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'max:255', 'unique:users,email'],
        'password' => ['required', 'confirmed', 'min:8'],
    ]);

    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
    ]);

    Auth::login($user);
    $request->session()->regenerate();

    return redirect()->route('agendamento.create');
})->middleware('guest')->name('register.store');

// Logout
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

Route::get('/diagnostico-hospedagem', function () {
    $requiredSchema = [
        'users' => ['name', 'email', 'password'],
        'servicos' => ['nome', 'valor', 'duracao'],
        'funcionarios' => ['nome'],
        'agendamentos' => ['user_id', 'data', 'horario', 'servico', 'profissional', 'servico_id', 'funcionario_id'],
    ];

    if (config('session.driver') === 'database') {
        $requiredSchema['sessions'] = ['id', 'payload'];
    }

    if (config('cache.default') === 'database') {
        $requiredSchema['cache'] = ['key', 'value'];
    }

    if (config('queue.default') === 'database') {
        $requiredSchema['jobs'] = ['queue', 'payload'];
    }

    $schemaStatus = [];
    $missingItems = [];
    $schemaCheckError = null;

    try {
        foreach ($requiredSchema as $table => $columns) {
            $tableExists = Schema::hasTable($table);
            $missingColumns = [];

            if ($tableExists) {
                foreach ($columns as $column) {
                    if (! Schema::hasColumn($table, $column)) {
                        $missingColumns[] = $column;
                        $missingItems[] = $table.'.'.$column;
                    }
                }
            } else {
                $missingItems[] = $table;
            }

            $schemaStatus[$table] = [
                'exists' => $tableExists,
                'missing_columns' => $missingColumns,
            ];

        }

    } catch (\Throwable $e) {
        $schemaCheckError = [
            'exception' => class_basename($e),
            'message' => $e->getMessage(),
        ];
    }

    $databaseStatus = [
        'connected' => false,
        'query_test' => false,
    ];

    try {
        DB::select('select 1 as ok');
        $databaseStatus['connected'] = true;
        $databaseStatus['query_test'] = true;
    } catch (\Throwable $e) {
        $databaseStatus['error'] = class_basename($e);
    }

    $loggingStatus = [
        'channel' => config('logging.default'),
        'stack' => env('LOG_STACK'),
        'laravel_log_exists' => file_exists(storage_path('logs/laravel.log')),
        'storage_logs_writable' => is_dir(storage_path('logs')) && is_writable(storage_path('logs')),
    ];

    try {
        error_log('SalaoBeauty diagnostico-hospedagem acessado');
        $loggingStatus['php_error_log_write'] = true;
    } catch (\Throwable $e) {
        $loggingStatus['php_error_log_write'] = false;
    }

    try {
        Log::warning('Diagnostico hospedagem acessado.', ['path' => request()->path()]);
        $loggingStatus['laravel_log_write'] = true;
    } catch (\Throwable $e) {
        $loggingStatus['laravel_log_write'] = false;
        $loggingStatus['laravel_log_error'] = class_basename($e);
    }

    return response()->json([
        'build' => '2026-06-26-diagnostic-v1',
        'app' => [
            'env' => app()->environment(),
            'debug' => config('app.debug'),
            'url' => config('app.url'),
            'php' => PHP_VERSION,
            'laravel' => app()->version(),
        ],
        'paths' => [
            'register' => route('register', [], false),
            'agendamento' => route('agendamento.create', [], false),
            'storage_logs' => storage_path('logs'),
            'storage_sessions' => storage_path('framework/sessions'),
        ],
        'runtime' => [
            'session_driver' => config('session.driver'),
            'cache_store' => config('cache.default'),
            'queue_connection' => config('queue.default'),
            'storage_framework_writable' => is_dir(storage_path('framework')) && is_writable(storage_path('framework')),
            'storage_sessions_writable' => is_dir(storage_path('framework/sessions')) && is_writable(storage_path('framework/sessions')),
            'bootstrap_cache_writable' => is_dir(base_path('bootstrap/cache')) && is_writable(base_path('bootstrap/cache')),
        ],
        'database' => $databaseStatus,
        'logging' => $loggingStatus,
        'schema' => $schemaStatus,
        'schema_check_error' => $schemaCheckError,
        'missing_items' => $missingItems,
    ]);
})->name('diagnostico.hospedagem');

// Rotas protegidas
Route::middleware('auth')->group(function () {
    Route::get('/agendamento', [AgendamentoController::class, 'create'])->name('agendamento.create');
    Route::post('/agendamento', [AgendamentoController::class, 'store'])->name('agendamento.store');
    Route::get('/agendamento/horarios-disponiveis', [AgendamentoController::class, 'horariosDisponiveis'])->name('agendamento.horarios');
    Route::get('/meus-agendamentos', [AgendamentoController::class, 'meusAgendamentos'])->name('agendamento.meus');

    Route::middleware('can:access-owner-area')->group(function () {
        Route::get('/dona/painel', [OwnerController::class, 'dashboard'])->name('owner.dashboard');

        Route::put('/dona/agendamentos/{agendamento}', [OwnerController::class, 'updateAgendamento'])->name('owner.agendamentos.update');
        Route::delete('/dona/agendamentos/{agendamento}', [OwnerController::class, 'destroyAgendamento'])->name('owner.agendamentos.destroy');

        Route::post('/dona/servicos', [ServicoController::class, 'store'])->name('owner.servicos.store');
        Route::put('/dona/servicos/{servico}', [ServicoController::class, 'update'])->name('owner.servicos.update');
        Route::delete('/dona/servicos/{servico}', [ServicoController::class, 'destroy'])->name('owner.servicos.destroy');

        Route::post('/dona/profissionais', [FuncionarioController::class, 'store'])->name('owner.funcionarios.store');
        Route::put('/dona/profissionais/{funcionario}', [FuncionarioController::class, 'update'])->name('owner.funcionarios.update');
        Route::delete('/dona/profissionais/{funcionario}', [FuncionarioController::class, 'destroy'])->name('owner.funcionarios.destroy');
    });
});


Route::middleware(['auth', 'can:access-admin-area'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin-dashboard');
    })->name('dashboard');
});
