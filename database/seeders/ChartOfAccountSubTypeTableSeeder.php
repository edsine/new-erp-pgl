<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChartOfAccountSubType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ChartOfAccountSubTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'Current Assets', 'type' => 1],
            ['name' => 'Non-Current Assets', 'type' => 1],
            ['name' => 'Current Liabilities', 'type' => 2],
            ['name' => 'Non-Current Liabilities', 'type' => 2],
            ['name' => 'Equity', 'type' => 3],
        ];

        ChartOfAccountSubType::insert($data);
    }
}
