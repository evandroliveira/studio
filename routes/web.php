<?php
// Rotas protegidas para exibir o formulário de agendamento
Route::middleware('auth')->group(function () {
    Route::get('/agendamento', function () {
        return view('agendamento');
    });
    Route::post('/agendamento', function (Request $request) {
        $request->validate([
            'data' => 'required|date',
            'horario' => 'required',
            'servico' => 'required|string|max:255',
        ]);

        \App\Models\Agendamento::create([
            'user_id' => auth()->id(),
            'data' => $request->data,
            'horario' => $request->horario,
            'servico' => $request->servico,
        ]);

        return back()->with('success', 'Agendamento realizado com sucesso!');
    });
});


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

use Illuminate\Http\Request;

Route::post('/login', function (Request $request) {
    $credentials = $request->only('email', 'password');
    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('/agendamento');
    }
    return back()->withErrors(['email' => 'E-mail ou senha inválidos'])->withInput();
});

// Logout
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

// Rotas protegidas
Route::middleware('auth')->group(function () {
    // Formulário de agendamento
    Route::get('/agendamento', function () {
        return view('agendamento');
    });

    // Salvar agendamento
    Route::post('/agendamento', function (Request $request) {
        $request->validate([
            'data' => 'required|date',
            'horario' => 'required',
            'servico' => 'required|string|max:255',
        ]);

        \App\Models\Agendamento::create([
            'user_id' => auth()->id(),
            'data' => $request->data,
            'horario' => $request->horario,
            'servico' => $request->servico,
        ]);

        return back()->with('success', 'Agendamento realizado com sucesso!');
    });

    // Listar agendamentos do usuário logado
    Route::get('/meus-agendamentos', function () {
        $agendamentos = \App\Models\Agendamento::where('user_id', auth()->id())->orderBy('data', 'desc')->orderBy('horario')->get();
        return view('meus-agendamentos', compact('agendamentos'));
    });
});