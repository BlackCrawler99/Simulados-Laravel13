<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Exports\LeadsExport;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Cartões de Resumo (KPIs)
        $totalLeads = User::where('is_admin', false)->count();
        $totalExams = Exam::whereNotNull('completed_at')->count();
        $avgScore = Exam::whereNotNull('completed_at')->avg('score');

        // 2. Dados para o Gráfico (Cadastros nos últimos 7 dias)
        $last7Days = collect([]);
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $count = User::where('is_admin', false)->whereDate('created_at', $date)->count();
            
            $last7Days->push([
                'date' => $date->format('d/m'),
                'count' => $count
            ]);
        }

        // Prepara as variáveis para o JavaScript (Chart.js)
        $chartLabels = $last7Days->pluck('date');
        $chartData = $last7Days->pluck('count');

        return view('admin.dashboard', compact('totalLeads', 'totalExams', 'avgScore', 'chartLabels', 'chartData'));
    }
    public function export()
    {
        return Excel::download(new LeadsExport, 'relatorio_captacao_leads_' . date('d-m-Y') . '.xlsx');
    }
}