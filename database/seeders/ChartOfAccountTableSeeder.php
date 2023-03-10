<?php

namespace Database\Seeders;

use App\Models\ChartOfAccount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChartOfAccountTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'Bank checking account', 'type' => 1, 'sub_type' => 1, 'sub_type_level_2' => 1, 'created_by' => 1],
            ['name' => 'Bank savings account', 'type' => 1, 'sub_type' => 1, 'sub_type_level_2' => 1, 'created_by' => 1],
            ['name' => 'Online savings account', 'type' => 1, 'sub_type' => 1, 'sub_type_level_2' => 1, 'created_by' => 1],
            ['name' => 'Petty cash account', 'type' => 1, 'sub_type' => 1, 'sub_type_level_2' => 1, 'created_by' => 1],
            ['name' => 'Paypal account', 'type' => 1, 'sub_type' => 1, 'sub_type_level_2' => 1, 'created_by' => 1],


            ['name' => 'Short term marketable securities', 'type' => 1, 'sub_type' => 1, 'sub_type_level_2' => 2, 'created_by' => 1],

            ['name' => 'Accounts receivable', 'type' => 1, 'sub_type' => 1, 'sub_type_level_2' => 3, 'created_by' => 1],
            ['name' => 'Allowance for doubtful debts account', 'type' => 1, 'sub_type' => 1, 'sub_type_level_2' => 3, 'created_by' => 1],


            ['name' => 'Raw materials', 'type' => 1, 'sub_type' => 1, 'sub_type_level_2' => 4, 'created_by' => 1],
            ['name' => 'Work in progress', 'type' => 1, 'sub_type' => 1, 'sub_type_level_2' => 4, 'created_by' => 1],
            ['name' => 'Finished goods', 'type' => 1, 'sub_type' => 1, 'sub_type_level_2' => 4, 'created_by' => 1],


            ['name' => 'Other receivables', 'type' => 1, 'sub_type' => 1, 'sub_type_level_2' => 5, 'created_by' => 1],
            ['name' => 'Prepayments', 'type' => 1, 'sub_type' => 1, 'sub_type_level_2' => 5, 'created_by' => 1],


            ['name' => 'Long term marketable securities', 'type' => 1, 'sub_type' => 2, 'sub_type_level_2' => 6, 'created_by' => 1],


            ['name' => 'Property', 'type' => 1, 'sub_type' => 2, 'sub_type_level_2' => 7, 'created_by' => 1],
            ['name' => 'Property Depreciation', 'type' => 1, 'sub_type' => 2, 'sub_type_level_2' => 7, 'created_by' => 1],
            ['name' => 'Plant', 'type' => 1, 'sub_type' => 2, 'sub_type_level_2' => 7, 'created_by' => 1],
            ['name' => 'Plant depreciation', 'type' => 1, 'sub_type' => 2, 'sub_type_level_2' => 7, 'created_by' => 1],
            ['name' => 'Equipment', 'type' => 1, 'sub_type' => 2, 'sub_type_level_2' => 7, 'created_by' => 1],
            ['name' => 'Equipment depreciation', 'type' => 1, 'sub_type' => 2, 'sub_type_level_2' => 7, 'created_by' => 1],


            ['name' => 'Goodwill', 'type' => 1, 'sub_type' => 2, 'sub_type_level_2' => 8, 'created_by' => 1],


            ['name' => 'Intellectual property', 'type' => 1, 'sub_type' => 2, 'sub_type_level_2' => 9, 'created_by' => 1],
            ['name' => 'Intellectual property amortization', 'type' => 1, 'sub_type' => 2, 'sub_type_level_2' => 9, 'created_by' => 1],


            ['name' => 'Other assets', 'type' => 1, 'sub_type' => 2, 'sub_type_level_2' => 10, 'created_by' => 1],


            ['name' => 'Notes payable', 'type' => 2, 'sub_type' => 3, 'sub_type_level_2' => 11, 'created_by' => 1],
            ['name' => 'Accounts payable', 'type' => 2, 'sub_type' => 3, 'sub_type_level_2' => 12, 'created_by' => 1],
            ['name' => 'Payroll payable', 'type' => 2, 'sub_type' => 3, 'sub_type_level_2' => 13, 'created_by' => 1],
            ['name' => 'Interest payables', 'type' => 2, 'sub_type' => 3, 'sub_type_level_2' => 13, 'created_by' => 1],
            ['name' => 'Accrued expenses', 'type' => 2, 'sub_type' => 3, 'sub_type_level_2' => 13, 'created_by' => 1],
            ['name' => 'Unearned revenue', 'type' => 2, 'sub_type' => 3, 'sub_type_level_2' => 13, 'created_by' => 1],
            ['name' => 'Sales Tax payable', 'type' => 2, 'sub_type' => 3, 'sub_type_level_2' => 13, 'created_by' => 1],
            ['name' => 'Purchase Tax payable', 'type' => 2, 'sub_type' => 3, 'sub_type_level_2' => 13, 'created_by' => 1],
            ['name' => 'Payroll tax payable', 'type' => 2, 'sub_type' => 3, 'sub_type_level_2' => 13, 'created_by' => 1],
            ['name' => 'Income tax payable', 'type' => 2, 'sub_type' => 3, 'sub_type_level_2' => 13, 'created_by' => 1],
            ['name' => 'Mortgage loan', 'type' => 2, 'sub_type' => 4, 'sub_type_level_2' => 14, 'created_by' => 1],
            ['name' => 'Bonds payable', 'type' => 2, 'sub_type' => 4, 'sub_type_level_2' => 15, 'created_by' => 1],



            ['name' => 'Common stock', 'type' => 3, 'sub_type' => 5, 'sub_type_level_2' => 16, 'created_by' => 1],
            ['name' => 'Retained earnings', 'type' => 3, 'sub_type' => 5, 'sub_type_level_2' => 17, 'created_by' => 1],

        ];

        ChartOfAccount::insert($data);
    }
}
