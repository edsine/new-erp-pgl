<?php

namespace Database\Seeders;

use App\Models\Tax;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'Works/Construction', 'rate' => '11.0', 'created_by' => 1],
            ['name' => 'Consultancy/ICT', 'rate' => '18.5', 'created_by' => 1],
            ['name' => 'Goods/Supply', 'rate' => '13.5', 'created_by' => 1],
        ];

        Tax::insert($data);
    }
}
