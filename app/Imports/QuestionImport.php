<?php

namespace App\Imports;

use App\Models\Question;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class QuestionImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        $currentQuestion = null;
        $options = [];

        foreach ($rows as $index => $row) {
            // Pula a primeira linha (cabeçalhos)
            if ($index === 0) continue;

            // Mapeamento atualizado das colunas do Excel
            $area        = $row[0] ?? null; // Coluna A
            $enunciado   = $row[1] ?? null; // Coluna B
            $alternativa = $row[2] ?? null; // Coluna C
            $correta     = strtolower(trim($row[3] ?? '')); // Coluna D
            $linkImagem  = $row[4] ?? null; // Coluna E

            // Se a coluna B (Enunciado) tem texto, significa que começou uma nova questão
            if (!empty($enunciado)) {
                // Se já tínhamos uma questão anterior sendo montada, salva ela no banco
                $this->saveQuestion($currentQuestion, $options);

                // Inicia a nova questão
                $currentQuestion = [
                    'area'      => !empty($area) ? trim($area) : 'Geral',
                    'statement' => $enunciado,
                    'image'     => $linkImagem,
                ];
                $options = []; // Zera as alternativas
            }

            // Se a coluna C (Alternativa) tem texto, adiciona como alternativa da questão atual
            if (!empty($alternativa)) {
                $options[] = [
                    'text'       => $alternativa,
                    'is_correct' => ($correta === 'sim'),
                ];
            }
        }

        // Fim do arquivo: salva a última questão que ficou "pendente" no loop
        $this->saveQuestion($currentQuestion, $options);
    }

    private function saveQuestion($questionData, $optionsData)
    {
        // Só salva se tiver enunciado e alternativas
        if (!$questionData || empty($optionsData)) return;

        $question = Question::create([
            'area'      => $questionData['area'], // Salvando a nova coluna no banco
            'statement' => $questionData['statement'],
            'image'     => $questionData['image'],
        ]);

        foreach ($optionsData as $opt) {
            $question->options()->create([
                'text'       => $opt['text'],
                'is_correct' => $opt['is_correct'],
            ]);
        }
    }
}