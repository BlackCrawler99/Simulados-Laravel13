<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class VocationalTemplateExport implements FromArray, WithColumnWidths, WithStyles
{
    public function array(): array
    {
        return [
            // Cabeçalhos (Linha 1)
            ['Pergunta', 'Respostas', 'Área'], 
            
            // Exemplo da Questão 1
            ['O que você prefere fazer no tempo livre?', 'Ler sobre descobertas científicas', 'Biológicas'],
            ['', 'Desenhar ou pintar', 'Artes'],
            ['', 'Resolver quebra-cabeças lógicos', 'Exatas'],
            ['', 'Debater sobre atualidades', 'Humanas'],

            // Pula uma linha para facilitar a visualização e separação
            ['', '', ''],
            
            // Exemplo da Questão 2
            ['Qual ambiente de trabalho te atrai mais?', 'Laboratório ou clínica', 'Biológicas'],
            ['', 'Estúdio de design ou agência', 'Artes'],
            ['', 'Escritório focado em tecnologia', 'Exatas'],
            ['', 'Trabalho em equipe e contato com o público', 'Humanas'],
        ];
    }

    /**
     * Define a largura ideal das colunas
     */
    public function columnWidths(): array
    {
        return [
            'A' => 60, // Pergunta precisa de mais espaço
            'B' => 50, // Respostas
            'C' => 25, // Área
        ];
    }

    /**
     * Aplica estilos na planilha (Cabeçalho em destaque)
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Estiliza a Linha 1 (O Cabeçalho)
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => 'FFFFFFFF'], // Letra Branca
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF4F46E5'], // Fundo com a cor Indigo-600 do Tailwind
                ],
            ],
        ];
    }
}