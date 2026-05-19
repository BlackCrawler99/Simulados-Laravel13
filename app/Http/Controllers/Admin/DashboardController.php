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
        // 1. Métricas dos Cards Superiores
        $totalLeads = User::where('is_admin', false)->count();
        $totalExams = Exam::where('completed_at', '!=', null)->count();
        $avgScore = Exam::where('completed_at', '!=', null)->avg('score') ?? 0;
        $totalIncompleteExams = Exam::where('completed_at', null)->count();

        // 2. Gráfico de Linha: Leads cadastrados nos últimos 7 dias
        $last7Days = collect(range(6, 0))->map(function($i) {
            return now()->subDays($i)->format('Y-m-d');
        });

        $leadsPerDay = User::where('is_admin', false)
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->selectRaw('DATE(created_at) as date, count(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date');

        $daysOfWeek = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];
        
        $chartLabels = $last7Days->map(function($date) use ($daysOfWeek) {
            return $daysOfWeek[Carbon::parse($date)->dayOfWeek];
        })->toArray();

        $chartData = $last7Days->map(function($date) use ($leadsPerDay) {
            return $leadsPerDay->get($date, 0);
        })->toArray();

        // 3. Gráfico de Barras: Distribuição de Notas (Faixas de 0 a 10)
        $notasDistribution = [
            Exam::whereNotNull('score')->whereBetween('score', [0, 2])->count(),
            Exam::whereNotNull('score')->whereRaw('score > 2 and score <= 4')->count(),
            Exam::whereNotNull('score')->whereRaw('score > 4 and score <= 6')->count(),
            Exam::whereNotNull('score')->whereRaw('score > 6 and score <= 8')->count(),
            Exam::whereNotNull('score')->whereRaw('score > 8 and score <= 10')->count(),
        ];

        // 4. Barras de Progresso: Média por Área de Conhecimento
        $rawStats = DB::table('answers')
            ->join('exams', 'answers.exam_id', '=', 'exams.id')
            ->join('questions', 'answers.question_id', '=', 'questions.id')
            ->whereNotNull('exams.completed_at')
            ->select('questions.area', 'answers.is_correct')
            ->get();

        $groupedAreas = [];

        // Agrupa e soma absolutamente qualquer nome que vier do banco de dados
        foreach ($rawStats as $stat) {
            $area = trim($stat->area);
            
            if (!isset($groupedAreas[$area])) {
                $groupedAreas[$area] = ['total' => 0, 'corretas' => 0];
            }

            $groupedAreas[$area]['total']++;
            if ($stat->is_correct) {
                $groupedAreas[$area]['corretas']++;
            }
        }

        $avgAreas = [];
        foreach ($groupedAreas as $area => $data) {
            $avgAreas[$area] = $data['total'] > 0 ? round(($data['corretas'] / $data['total']) * 10, 1) : 0.0;
        }

        arsort($avgAreas);

        // CORREÇÃO: Enviando de fato os dados processados para renderizar na View
        return view('admin.dashboard', compact(
            'totalLeads',
            'totalExams',
            'avgScore',
            'totalIncompleteExams',
            'chartLabels',
            'chartData',
            'notasDistribution',
            'avgAreas'
        ));
    } // Fecha adequadamente o escopo do método index

    public function export()
    {
        return Excel::download(new LeadsExport, 'relatorio_captacao_leads_' . date('d-m-Y') . '.xlsx');
    }
}