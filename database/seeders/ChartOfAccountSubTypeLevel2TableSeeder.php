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
            ['name' => 'Cash and cash equivalents', 'sub_type' => 1],
            ['name' => 'Short term marketable securities', 'sub_type' => 1],
            ['name' => 'Accounts receivable', 'sub_type' => 1],
            ['name' => 'Inventory', 'sub_type' => 1],
            ['name' => 'Other current assets', 'sub_type' => 1],
            ['name' => 'Long term marketable securities', 'sub_type' => 2],
            ['name' => 'Property, plant and equipment', 'sub_type' => 2],
            ['name' => 'Goodwill', 'sub_type' => 2],
            ['name' => 'Intellectual property', 'sub_type' => 2],
            ['name' => 'Other non-current assets', 'sub_type' => 2],
            ['name' => 'Notes payable', 'sub_type' => 3],
            ['name' => 'Accounts payable', 'sub_type' => 3],
            ['name' => 'Other current liabilities', 'sub_type' => 3],
            ['name' => 'Mortgages', 'sub_type' => 4],
            ['name' => 'Bonds', 'sub_type' => 4],
            ['name' => 'Capital', 'sub_type' => 5],
            ['name' => 'Retained earnings', 'sub_type' => 5],
        ];

        ChartOfAccountSubTypeLevel2::insert($data);
    }
}
