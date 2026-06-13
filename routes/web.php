<?php

use App\Http\Controllers\AgendamentoController;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\ServicoController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;


// Página inicial
Route::get('/', function () {
    return view('welcome');
});

// Página de produtos
Route::get('/produtos', function () {
    return view('produtos');
});

// Login
Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
        'remember' => ['nullable', 'boolean'],
    ]);

    $user = User::where('email', $credentials['email'])->first();

    if ($user) {
        $password = $credentials['password'];
        $isValidPassword = false;

        try {
            $isValidPassword = Hash::check($password, $user->password);
        } catch (\RuntimeException $e) {
            $isValidPassword = password_verify($password, $user->password)
                || hash_equals((string) $user->password, (string) $password);
        }

        if ($isValidPassword) {
            if (! password_get_info((string) $user->password)['algo']) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }

            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            if ($user->can('access-owner-area')) {
                return redirect()->intended('/dona/painel');
            }

            return redirect()->intended('/agendamento');
        }
    }

    return back()->withErrors(['email' => 'E-mail ou senha inválidos'])->withInput();
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

// Rotas protegidas
Route::middleware('auth')->group(function () {
    Route::get('/agendamento', [AgendamentoController::class, 'create'])->name('agendamento.create');
    Route::post('/agendamento', [AgendamentoController::class, 'store'])->name('agendamento.store');
    Route::get('/agendamento/horarios-disponiveis', [AgendamentoController::class, 'horariosDisponiveis'])->name('agendamento.horarios');
    Route::get('/meus-agendamentos', [AgendamentoController::class, 'meusAgendamentos'])->name('agendamento.meus');

    Route::middleware('can:access-owner-area')->group(function () {
        Route::get('/dona/painel', [OwnerController::class, 'dashboard'])->name('owner.dashboard');

        Route::post('/dona/servicos', [ServicoController::class, 'store'])->name('owner.servicos.store');
        Route::put('/dona/servicos/{servico}', [ServicoController::class, 'update'])->name('owner.servicos.update');
        Route::delete('/dona/servicos/{servico}', [ServicoController::class, 'destroy'])->name('owner.servicos.destroy');

        Route::post('/dona/profissionais', [FuncionarioController::class, 'store'])->name('owner.funcionarios.store');
        Route::put('/dona/profissionais/{funcionario}', [FuncionarioController::class, 'update'])->name('owner.funcionarios.update');
        Route::delete('/dona/profissionais/{funcionario}', [FuncionarioController::class, 'destroy'])->name('owner.funcionarios.destroy');
    });
});