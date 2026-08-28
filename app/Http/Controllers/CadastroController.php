<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class CadastroController extends Controller
{
    // Método para exibir a view de cadastro
    public function create()
    {
        return view('pages.cadastro'); // O nome do seu arquivo blade atual
    }

    // Método para processar o formulário
    public function store(Request $request)
    {
        // Validação dos dados recebidos do formulário
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string',
        ]);

        // Criação do usuário no banco de dados
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'datacadastro' => now(), // Preenchendo o campo customizado exigido pela sua migration[cite: 1]
        ]);

        // Autentica o usuário imediatamente após o cadastro
        Auth::login($user);

        // Redireciona para a home
        return redirect()->route('home');
    }
}