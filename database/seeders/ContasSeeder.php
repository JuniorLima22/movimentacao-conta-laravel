<?php

namespace Database\Seeders;

use App\Models\Conta;
use Illuminate\Database\Seeder;

class ContasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $contas = [
            [
                'pessoa_id' => 1,
                'numero' => 2345321343
            ],
            [
                'pessoa_id' => 2,
                'numero' => 2345321355
            ],
            [
                'pessoa_id' => 3,
                'numero' => 2345321388
            ],
        ];
        foreach ($contas as $key => $value) {
            $user = Conta::create($value);
        }
    }
}
