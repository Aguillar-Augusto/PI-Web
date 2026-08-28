<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Método para exibir a view de login
    public function index()
    {
        return view('pages.login'); // O nome do seu arquivo blade de login
    }

    // Método para processar a tentativa de login
    public function authenticate(Request $request)
    {
        // Validação básica dos dados recebidos
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Tenta autenticar o usuário com as credenciais fornecidas
        if (Auth::attempt($credentials)) {
            // Regera a sessão para evitar session fixation
            $request->session()->regenerate();

            // Redireciona para a home
            return redirect()->route('home');
        }

        // Se falhar, retorna para a página de login com um erro
        return back()->withErrors([
            'email' => 'As credenciais fornecidas não correspondem aos nossos registros.',
        ])->onlyInput('email');
    }

    // Método para fazer logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}