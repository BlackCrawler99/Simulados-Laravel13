<?php

namespace App\Imports;

use App\Models\VocationalQuestion;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class VocationalImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        $currentQuestion = null;
        $options = [];

        foreach ($rows as $index => $row) {
            // Pula a primeira linha (cabeçalhos)
            if ($index === 0) continue;

            // Mapeamento das 3 colunas do Excel
            $pergunta = $row[0] ?? null; // Coluna A
            $resposta = $row[1] ?? null; // Coluna B
            $area     = $row[2] ?? null; // Coluna C

            // Se a coluna A (Pergunta) tem texto, começou uma nova questão
            if (!empty(trim($pergunta))) {
                // Se já tínhamos uma questão anterior sendo montada, salva ela no banco
                $this->saveQuestion($currentQuestion, $options);

                // Inicia a nova questão
                $currentQuestion = [
                    'text' => trim($pergunta),
                ];
                $options = []; // Zera as alternativas para a nova pergunta
            }

            // Se a coluna B (Resposta) e C (Área) têm texto, adiciona como alternativa da questão atual
            if (!empty(trim($resposta)) && !empty(trim($area))) {
                $options[] = [
                    'text' => trim($resposta),
                    'area' => trim($area),
                ];
            }
        }

        // Fim do arquivo: salva a última questão que ficou "pendente" no loop
        $this->saveQuestion($currentQuestion, $options);
    }

    private function saveQuestion($questionData, $optionsData)
    {
        // Só salva se tiver a pergunta e as alternativas
        if (!$questionData || empty($optionsData)) return;

        $question = VocationalQuestion::create([
            'text'      => $questionData['text'],
            'is_active' => true,
        ]);

        foreach ($optionsData as $opt) {
            $question->options()->create([
                'text'   => $opt['text'],
                'area'   => $opt['area'],
                'points' => 1,
            ]);
        }
    }
}