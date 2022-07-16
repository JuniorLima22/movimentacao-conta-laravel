<?php

namespace Database\Seeders;

use App\Models\Historico;
use Illuminate\Database\Seeder;

class HistoricosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $historicos = [
            [
                'pessoa_id' => 1,
                'conta_id' => 1,
                'tipo' => 'E',
                'valor' => 100.0
            ],
            [
                'pessoa_id' => 1,
                'conta_id' => 1,
                'tipo' => 'S',
                'valor' => 5.0
            ],
            [
                'pessoa_id' => 1,
                'conta_id' => 1,
                'tipo' => 'E',
                'valor' => 25.0
            ],
            [
                'pessoa_id' => 2,
                'conta_id' => 2,
                'tipo' => 'E',
                'valor' => 100.0
            ],
            [
                'pessoa_id' => 2,
                'conta_id' => 2,
                'tipo' => 'S',
                'valor' => 35.0
            ],
            [
                'pessoa_id' => 2,
                'conta_id' => 2,
                'tipo' => 'E',
                'valor' => 250.0
            ],
            [
                'pessoa_id' => 3,
                'conta_id' => 3,
                'tipo' => 'E',
                'valor' => 250.0
            ],
            [
                'pessoa_id' => 3,
                'conta_id' => 3,
                'tipo' => 'S',
                'valor' => 80.0
            ],
            [
                'pessoa_id' => 3,
                'conta_id' => 3,
                'tipo' => 'S',
                'valor' => 20.0
            ],
        ];
        foreach ($historicos as $key => $value) {
            $user = Historico::create($value);
        }
    }
}
