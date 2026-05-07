<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\Option; // Importante adicionar o Model de Option

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        // Array com os dados falsos. Note que retiramos o campo 'subject' (matéria).
        $questionsData = [
            [
                'statement' => 'Um fazendeiro tem 30 vacas. Todas, exceto 18, morreram. Quantas vacas restaram?',
                'options' => [
                    ['text' => '12', 'is_correct' => false],
                    ['text' => '18', 'is_correct' => true],
                    ['text' => '30', 'is_correct' => false],
                    ['text' => '0', 'is_correct' => false],
                    ['text' => 'Nenhuma das alternativas', 'is_correct' => false],
                ]
            ],
            [
                'statement' => 'Em uma corrida, você ultrapassa o segundo colocado. Em qual posição você fica?',
                'options' => [
                    ['text' => 'Primeiro', 'is_correct' => false],
                    ['text' => 'Segundo', 'is_correct' => true],
                    ['text' => 'Terceiro', 'is_correct' => false],
                    ['text' => 'Último', 'is_correct' => false],
                    ['text' => 'Penúltimo', 'is_correct' => false],
                ]
            ],
            [
                'statement' => 'Qual é o resultado da expressão: 8 ÷ 2(2 + 2)?',
                'options' => [
                    ['text' => '1', 'is_correct' => false],
                    ['text' => '8', 'is_correct' => false],
                    ['text' => '16', 'is_correct' => true],
                    ['text' => '4', 'is_correct' => false],
                    ['text' => '0', 'is_correct' => false],
                ]
            ],
            [
                'statement' => 'Se três gatos matam três ratos em três minutos, quanto tempo levarão cem gatos para matar cem ratos?',
                'options' => [
                    ['text' => '100 minutos', 'is_correct' => false],
                    ['text' => '3 minutos', 'is_correct' => true],
                    ['text' => '33 minutos', 'is_correct' => false],
                    ['text' => '1 minuto', 'is_correct' => false],
                    ['text' => '300 minutos', 'is_correct' => false],
                ]
            ],
            [
                'statement' => 'Quantos meses têm 28 dias?',
                'options' => [
                    ['text' => '1 (Fevereiro)', 'is_correct' => false],
                    ['text' => 'Apenas em anos bissextos', 'is_correct' => false],
                    ['text' => '6 meses', 'is_correct' => false],
                    ['text' => '12 meses', 'is_correct' => true],
                    ['text' => 'Nenhum', 'is_correct' => false],
                ]
            ],
        ];

        // Percorre o array e insere no banco
        foreach ($questionsData as $data) {
            // Cria a questão (sem a matéria)
            $question = Question::create([
                'statement' => $data['statement']
            ]);

            // Para cada questão criada, insere as 5 alternativas vinculadas a ela
            foreach ($data['options'] as $option) {
                $question->options()->create([
                    'text' => $option['text'],
                    'is_correct' => $option['is_correct']
                ]);
            }
        }
    }
}