<?php

namespace Database\Seeders;

use App\Models\Pessoa;
use Illuminate\Database\Seeder;

class PessoasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pessoas = [
            [
                'nome' =>  'Marcelo Ramos' ,
                'cpf' =>  '01054804010',
                'cep' =>  '88705001',
                'numero' =>  '10',
                'logradouro' =>  'Rua' ,
                'bairro' =>  'Passagem',
                'estado' =>  'SC',
                'municipio' =>  'Tubarão',
            ],
            [
                'nome' =>  'Renato Silva' ,
                'cpf' =>  '01054804010',
                'cep' =>  '88705001',
                'numero' =>  '10',
                'logradouro' =>  'Rua' ,
                'bairro' =>  'Passagem',
                'estado' =>  'SC',
                'municipio' =>  'Tubarão',
            ],
            [
                'nome' =>  'Maria Cordeiro' ,
                'cpf' =>  '01054804010',
                'cep' =>  '88705001',
                'numero' =>  '10',
                'logradouro' =>  'Rua' ,
                'bairro' =>  'Passagem',
                'estado' =>  'SC',
                'municipio' =>  'Tubarão',
            ]
        ];
        foreach ($pessoas as $key => $value) {
            $user = Pessoa::create($value);
        }
    }
}
