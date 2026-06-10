<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;
use App\Models\SchoolClass;

class SchoolSeeder extends Seeder
{
    public function run()
    {
        $escolas = [
            ['name' => 'Colégio Estadual Souza Naves', 'city' => 'Rolândia'],
            ['name' => 'Colégio Cívico-Militar Kennedy', 'city' => 'Rolândia'],
            ['name' => 'Colégio Estadual Érico Veríssimo', 'city' => 'Cambé'],
            ['name' => 'Colégio Estadual Attilio Codato', 'city' => 'Cambé'],
            ['name' => 'Colégio Estadual Emílio de Menezes', 'city' => 'Arapongas'],
            ['name' => 'Colégio Estadual Marquês de Caravelas', 'city' => 'Arapongas'],
        ];

        foreach ($escolas as $dadosEscola) {
            // Cria a escola e já ativa o módulo
            $school = School::create([
                'name' => $dadosEscola['name'],
                'city' => $dadosEscola['city'],
                'module_colegios' => true 
            ]);

            // Cria 3 turmas padrão para cada escola poder ser testada
            $turmas = ['3º Ano A', '3º Ano B', 'Terceirão'];
            foreach ($turmas as $nomeTurma) {
                SchoolClass::create([
                    'school_id' => $school->id,
                    'name' => $nomeTurma,
                    'period' => 'Matutino'
                ]);
            }
        }
    }
}