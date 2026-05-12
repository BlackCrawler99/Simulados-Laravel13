<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Busca o usuário pelo nome ou email, e mantém o termo de pesquisa na paginação
        $users = User::when($search, function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        })->latest()->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users', 'search'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // O unique ignora o ID do próprio usuário para ele poder salvar sem alterar o email
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('admin.users.index')->with('status', 'Cadastro do usuário atualizado com sucesso!');
    }

    public function toggleAdmin(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->withErrors(['error' => 'Você não pode alterar seu próprio privilégio de administrador.']);
        }

        $user->update(['is_admin' => !$user->is_admin]);

        $status = $user->is_admin ? 'promovido a Administrador' : 'removido do cargo de Administrador';
        return back()->with('status', "Usuário {$user->name} foi {$status}.");
    }

    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->withErrors(['error' => 'Você não pode excluir sua própria conta.']);
        }

        $user->exams()->delete();
        $user->delete();

        return back()->with('status', 'Usuário e todos os seus dados foram excluídos permanentemente.');
    }
}