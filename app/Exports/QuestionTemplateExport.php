<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class QuestionTemplateExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    // Define os cabeçalhos
    public function headings(): array
    {
        return [
            'Enunciado', 
            'Alternativas', 
            'Correta', 
            'Link da Imagem (Opcional)'
        ];
    }

    // Preenche os dados
    public function array(): array
    {
        return [
            ['Qual é a capital do Brasil?', 'Rio de Janeiro', '', 'https://link-da-imagem.com/foto.jpg'],
            ['', 'São Paulo', '', ''],
            ['', 'Brasília', 'sim', ''],
            ['', 'Salvador', '', ''],
            ['', 'Curitiba', '', ''],
            ['', '', '', ''], // Linha vazia
            ['Quanto é 2 + 2?', '1', '', ''],
            ['', '2', '', ''],
            ['', '3', '', ''],
            ['', '4', 'sim', ''],
            ['', '5', '', ''],
        ];
    }

    // Aplica o design na planilha
    public function styles(Worksheet $sheet)
    {
        // Pega qual é a última linha e a última coluna preenchida
        $lastRow = $sheet->getHighestRow();
        $lastCol = $sheet->getHighestColumn();

        // 1. Estiliza o Cabeçalho (Primeira Linha)
        $sheet->getStyle('A1:' . $lastCol . '1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFFFF'], // Letra Branca
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF4F46E5'], // Fundo Indigo (O azul do seu painel)
            ],
        ]);

        // 2. Coloca bordas finas em TODA a tabela preenchida
        $sheet->getStyle('A1:' . $lastCol . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'], // Borda Preta
                ],
            ],
        ]);

        return [];
    }
}