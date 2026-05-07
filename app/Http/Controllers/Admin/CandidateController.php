<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; // Importa o controlador base
use App\Models\User; // Importa o modelo de Usuário
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('is_admin', false)
            ->withCount('exams')
            ->withAvg('exams', 'score');

        // 1. LÓGICA DE BUSCA
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // 2. LÓGICA DE ORDENAÇÃO
        // Pegamos o campo e a direção da URL, definindo 'created_at' e 'desc' como padrão
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');

        // Por segurança, listamos quais colunas podem ser ordenadas (evita SQL Injection)
        $allowedSorts = ['name', 'email', 'created_at'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection);
        }

        // 3. LÓGICA DE PAGINAÇÃO
        // Trazemos 10 registros por página. 
        // O appends() garante que, se mudarmos de página, a busca e a ordenação não se percam!
        $candidates = $query->paginate(10)->appends($request->query());

        return view('admin.candidates.index', compact('candidates'));
    }
}

