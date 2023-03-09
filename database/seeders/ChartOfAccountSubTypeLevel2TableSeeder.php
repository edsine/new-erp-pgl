<?php

namespace Database\Seeders;

use App\Models\ChartOfAccountSubTypeLevel2;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChartOfAccountSubTypeLevel2TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'Inventory', 'sub_type' => 1],
            ['name' => 'Bank & Cash', 'sub_type' => 1],
            ['name' => 'Pre-Payment', 'sub_type' => 1],
            ['name' => 'Property, Plant & Equipment', 'sub_type' => 2],
        ];

        ChartOfAccountSubTypeLevel2::insert($data);
    }
}
