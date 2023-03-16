<?php

namespace Database\Seeders;

use App\Models\ChartOfAccountType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChartOfAccountTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'Assets', 'created_by' => 1],
            ['name' => 'Liabilities', 'created_by' => 1],
            ['name' => 'Equity', 'created_by' => 1],
        ];

        ChartOfAccountType::insert($data);
    }
}
