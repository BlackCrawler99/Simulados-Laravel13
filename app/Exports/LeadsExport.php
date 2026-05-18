<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class LeadsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    // 1. Busca os dados no banco (Apenas alunos, ignorando os Admins)
    public function collection()
    {
        return User::where('is_admin', false)->with('exams')->get();
    }

    // 2. Define o Cabeçalho do Excel (Adicionado o Telefone)
    public function headings(): array
    {
        return [
            'Nome do Candidato',
            'E-mail de Contato',
            'Telefone / WhatsApp',
            'Data de Cadastro',
            'Simulados Finalizados',
            'Melhor Nota'
        ];
    }

    // 3. Mapeia o que vai em cada coluna para cada aluno
    public function map($user): array
    {
        // Pega apenas os simulados que o aluno já terminou
        $completedExams = $user->exams->whereNotNull('score');
        
        // Descobre a melhor nota dele
        $bestScore = $completedExams->max('score');

        return [
            $user->name,
            $user->email,
            $user->phone, // Ajuste aqui se a coluna no seu banco se chamar 'whatsapp' ou 'celular'
            $user->created_at->format('d/m/Y H:i'),
            $completedExams->count(),
            $bestScore !== null ? number_format($bestScore, 1, ',', '') : 'Pendente',
        ];
    }

    // 4. Deixa o cabeçalho bonitão (Atualizado de A1:E1 para A1:F1)
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF4F46E5'],
            ],
        ]);

        return [];
    }
}