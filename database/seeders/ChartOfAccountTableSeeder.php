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
            ['name' => 'Bank account', 'type' => 1, 'sub_type' => 1, 'sub_type_level_2' => 1, 'created_by' => 1, 'code' => 100],
            // ['name' => 'Bank savings account', 'type' => 1, 'sub_type' => 1, 'sub_type_level_2' => 1, 'created_by' => 1, 'code' => 110],
            ['name' => 'Online savings account', 'type' => 1, 'sub_type' => 1, 'sub_type_level_2' => 1, 'created_by' => 1, 'code' => 120],
            ['name' => 'Petty cash account', 'type' => 1, 'sub_type' => 1, 'sub_type_level_2' => 1, 'created_by' => 1, 'code' => 130],
            ['name' => 'Paypal account', 'type' => 1, 'sub_type' => 1, 'sub_type_level_2' => 1, 'created_by' => 1, 'code' => 140],


            ['name' => 'Short term marketable securities', 'type' => 1, 'sub_type' => 1, 'sub_type_level_2' => 2, 'created_by' => 1, 'code' => 200],

            ['name' => 'Accounts receivable', 'type' => 1, 'sub_type' => 1, 'sub_type_level_2' => 3, 'created_by' => 1, 'code' => 300],
            ['name' => 'Allowance for doubtful debts account', 'type' => 1, 'sub_type' => 1, 'sub_type_level_2' => 3, 'created_by' => 1, 'code' => 310],


            ['name' => 'Raw materials', 'type' => 1, 'sub_type' => 1, 'sub_type_level_2' => 4, 'created_by' => 1, 'code' => 400],
            ['name' => 'Work in progress', 'type' => 1, 'sub_type' => 1, 'sub_type_level_2' => 4, 'created_by' => 1, 'code' => 410],
            ['name' => 'Finished goods', 'type' => 1, 'sub_type' => 1, 'sub_type_level_2' => 4, 'created_by' => 1, 'code' => 420],


            ['name' => 'Other receivables', 'type' => 1, 'sub_type' => 1, 'sub_type_level_2' => 5, 'created_by' => 1, 'code' => 500],
            ['name' => 'Prepayments', 'type' => 1, 'sub_type' => 1, 'sub_type_level_2' => 5, 'created_by' => 1, 'code' => 510],


            ['name' => 'Long term marketable securities', 'type' => 1, 'sub_type' => 2, 'sub_type_level_2' => 6, 'created_by' => 1, 'code' => 600],


            ['name' => 'Property', 'type' => 1, 'sub_type' => 2, 'sub_type_level_2' => 7, 'created_by' => 1, 'code' => 700],
            ['name' => 'Property Depreciation', 'type' => 1, 'sub_type' => 2, 'sub_type_level_2' => 7, 'created_by' => 1, 'code' => 710],
            ['name' => 'Plant', 'type' => 1, 'sub_type' => 2, 'sub_type_level_2' => 7, 'created_by' => 1, 'code' => 720],
            ['name' => 'Plant depreciation', 'type' => 1, 'sub_type' => 2, 'sub_type_level_2' => 7, 'created_by' => 1, 'code' => 730],
            ['name' => 'Equipment', 'type' => 1, 'sub_type' => 2, 'sub_type_level_2' => 7, 'created_by' => 1, 'code' => 740],
            ['name' => 'Equipment depreciation', 'type' => 1, 'sub_type' => 2, 'sub_type_level_2' => 7, 'created_by' => 1, 'code' => 750],
            ['name' => 'Motor vehicles', 'type' => 1, 'sub_type' => 2, 'sub_type_level_2' => 7, 'created_by' => 1, 'code' => 750],
            ['name' => 'Furniture and fittings', 'type' => 1, 'sub_type' => 2, 'sub_type_level_2' => 7, 'created_by' => 1, 'code' => 750],


            ['name' => 'Goodwill', 'type' => 1, 'sub_type' => 2, 'sub_type_level_2' => 8, 'created_by' => 1, 'code' => 800],


            ['name' => 'Intellectual property', 'type' => 1, 'sub_type' => 2, 'sub_type_level_2' => 9, 'created_by' => 1, 'code' => 810],
            ['name' => 'Intellectual property amortization', 'type' => 1, 'sub_type' => 2, 'sub_type_level_2' => 9, 'created_by' => 1, 'code' => 820],


            ['name' => 'Other assets', 'type' => 1, 'sub_type' => 2, 'sub_type_level_2' => 10, 'created_by' => 1, 'code' => 840],


            ['name' => 'Notes payable', 'type' => 2, 'sub_type' => 3, 'sub_type_level_2' => 11, 'created_by' => 1, 'code' => 900],
            ['name' => 'Accounts payable', 'type' => 2, 'sub_type' => 3, 'sub_type_level_2' => 12, 'created_by' => 1, 'code' => 1000],
            ['name' => 'Payroll payable', 'type' => 2, 'sub_type' => 3, 'sub_type_level_2' => 13, 'created_by' => 1, 'code' => 1100],
            ['name' => 'Interest payables', 'type' => 2, 'sub_type' => 3, 'sub_type_level_2' => 13, 'created_by' => 1, 'code' => 1110],
            ['name' => 'Accrued expenses', 'type' => 2, 'sub_type' => 3, 'sub_type_level_2' => 13, 'created_by' => 1, 'code' => 1120],
            ['name' => 'Unearned revenue', 'type' => 2, 'sub_type' => 3, 'sub_type_level_2' => 13, 'created_by' => 1, 'code' => 1130],
            ['name' => 'Sales Tax payable', 'type' => 2, 'sub_type' => 3, 'sub_type_level_2' => 13, 'created_by' => 1, 'code' => 1140],
            ['name' => 'Purchase Tax payable', 'type' => 2, 'sub_type' => 3, 'sub_type_level_2' => 13, 'created_by' => 1, 'code' => 1150],
            ['name' => 'Payroll tax payable', 'type' => 2, 'sub_type' => 3, 'sub_type_level_2' => 13, 'created_by' => 1, 'code' => 1160],
            ['name' => 'Income tax payable', 'type' => 2, 'sub_type' => 3, 'sub_type_level_2' => 13, 'created_by' => 1, 'code' => 1170],
            ['name' => 'Mortgage loan', 'type' => 2, 'sub_type' => 4, 'sub_type_level_2' => 14, 'created_by' => 1, 'code' => 1200],
            ['name' => 'Bonds payable', 'type' => 2, 'sub_type' => 4, 'sub_type_level_2' => 15, 'created_by' => 1, 'code' => 1210],



            ['name' => 'Common stock', 'type' => 3, 'sub_type' => 5, 'sub_type_level_2' => 16, 'created_by' => 1, 'code' => 1300],
            ['name' => 'Retained earnings', 'type' => 3, 'sub_type' => 5, 'sub_type_level_2' => 17, 'created_by' => 1, 'code' => 1400],



            ['name' => 'Income from project', 'type' => 4, 'sub_type' => 6, 'sub_type_level_2' => 18, 'created_by' => 1, 'code' => 2000],
            ['name' => 'Discount allowed', 'type' => 4, 'sub_type' => 6, 'sub_type_level_2' => 18, 'created_by' => 1, 'code' => 2100],
            ['name' => 'Gain on sale of assets', 'type' => 4, 'sub_type' => 6, 'sub_type_level_2' => 19, 'created_by' => 1, 'code' => 2200],
            ['name' => 'Interest income', 'type' => 4, 'sub_type' => 6, 'sub_type_level_2' => 19, 'created_by' => 1, 'code' => 2300],
            ['name' => 'Insurance claims', 'type' => 4, 'sub_type' => 6, 'sub_type_level_2' => 19, 'created_by' => 1, 'code' => 2400],
            ['name' => 'Rent income', 'type' => 4, 'sub_type' => 6, 'sub_type_level_2' => 19, 'created_by' => 1, 'code' => 2500],



            ['name' => 'Direct cost on project execution', 'type' => 5, 'sub_type' => 7, 'sub_type_level_2' => 20, 'created_by' => 1, 'code' => 3000],
            ['name' => 'Contigencies', 'type' => 5, 'sub_type' => 7, 'sub_type_level_2' => 20, 'created_by' => 1, 'code' => 3100],
            ['name' => 'Public Relation (PR)', 'type' => 5, 'sub_type' => 7, 'sub_type_level_2' => 20, 'created_by' => 1, 'code' => 3110],
            ['name' => 'Other Project Expenses', 'type' => 5, 'sub_type' => 7, 'sub_type_level_2' => 20, 'created_by' => 1, 'code' => 3120],



            ['type' => 5, 'sub_type' => 7, 'sub_type_level_2' => 21, 'created_by' => 1, 'code' => 3130, 'name' => 'Airtime'],
            ['type' => 5, 'sub_type' => 7, 'sub_type_level_2' => 21, 'created_by' => 1, 'code' => 3140, 'name' => 'Award Night'],
            ['type' => 5, 'sub_type' => 7, 'sub_type_level_2' => 21, 'created_by' => 1, 'code' => 3150, 'name' => 'Bank Charges'],
            ['type' => 5, 'sub_type' => 7, 'sub_type_level_2' => 21, 'created_by' => 1, 'code' => 3160, 'name' => 'CEO Expenses'],
            ['type' => 5, 'sub_type' => 7, 'sub_type_level_2' => 21, 'created_by' => 1, 'code' => 3170, 'name' => 'CEO House Expenses'],
            ['type' => 5, 'sub_type' => 7, 'sub_type_level_2' => 21, 'created_by' => 1, 'code' => 3180, 'name' => 'Cleaning Services'],
            ['type' => 5, 'sub_type' => 7, 'sub_type_level_2' => 21, 'created_by' => 1, 'code' => 3190, 'name' => 'Compliance Documents'],
            ['type' => 5, 'sub_type' => 7, 'sub_type_level_2' => 21, 'created_by' => 1, 'code' => 3200, 'name' => 'Consultancy Fee'],
            ['type' => 5, 'sub_type' => 7, 'sub_type_level_2' => 21, 'created_by' => 1, 'code' => 3210, 'name' => 'Consumables'],
            ['type' => 5, 'sub_type' => 7, 'sub_type_level_2' => 21, 'created_by' => 1, 'code' => 3220, 'name' => 'Corporate Social Responsibility'],
            ['type' => 5, 'sub_type' => 7, 'sub_type_level_2' => 21, 'created_by' => 1, 'code' => 3230, 'name' => 'Diesel'],
            ['type' => 5, 'sub_type' => 7, 'sub_type_level_2' => 21, 'created_by' => 1, 'code' => 3240, 'name' => 'Domain Hosting'],
            ['type' => 5, 'sub_type' => 7, 'sub_type_level_2' => 21, 'created_by' => 1, 'code' => 3250, 'name' => 'Fueling'],
            ['type' => 5, 'sub_type' => 7, 'sub_type_level_2' => 21, 'created_by' => 1, 'code' => 3260, 'name' => 'General Office Expense'],
            ['type' => 5, 'sub_type' => 7, 'sub_type_level_2' => 21, 'created_by' => 1, 'code' => 3270, 'name' => 'HONORARIUM'],
            ['type' => 5, 'sub_type' => 7, 'sub_type_level_2' => 21, 'created_by' => 1, 'code' => 3280, 'name' => 'Legal Fee'],
            ['type' => 5, 'sub_type' => 7, 'sub_type_level_2' => 21, 'created_by' => 1, 'code' => 3290, 'name' => 'Loan'],
            ['type' => 5, 'sub_type' => 7, 'sub_type_level_2' => 21, 'created_by' => 1, 'code' => 3300, 'name' => 'Logistics'],
            ['type' => 5, 'sub_type' => 7, 'sub_type_level_2' => 21, 'created_by' => 1, 'code' => 3310, 'name' => 'Medical Bill'],
            ['type' => 5, 'sub_type' => 7, 'sub_type_level_2' => 21, 'created_by' => 1, 'code' => 3320, 'name' => 'Office Maintenance'],
            ['type' => 5, 'sub_type' => 7, 'sub_type_level_2' => 21, 'created_by' => 1, 'code' => 3340, 'name' => 'Public Relation (PR)'],
            ['type' => 5, 'sub_type' => 7, 'sub_type_level_2' => 21, 'created_by' => 1, 'code' => 3350, 'name' => 'Prepaid'],
            ['type' => 5, 'sub_type' => 7, 'sub_type_level_2' => 21, 'created_by' => 1, 'code' => 3360, 'name' => 'Printer Maintenance'],
            ['type' => 5, 'sub_type' => 7, 'sub_type_level_2' => 21, 'created_by' => 1, 'code' => 3370, 'name' => 'Printing'],
            ['type' => 5, 'sub_type' => 7, 'sub_type_level_2' => 21, 'created_by' => 1, 'code' => 3380, 'name' => 'ROI Paid'],
            ['type' => 5, 'sub_type' => 7, 'sub_type_level_2' => 21, 'created_by' => 1, 'code' => 3390, 'name' => 'Salary'],
            ['type' => 5, 'sub_type' => 7, 'sub_type_level_2' => 21, 'created_by' => 1, 'code' => 4000, 'name' => 'Staff House Expenses'],
            ['type' => 5, 'sub_type' => 7, 'sub_type_level_2' => 21, 'created_by' => 1, 'code' => 4100, 'name' => 'Staff Welfare'],
            ['type' => 5, 'sub_type' => 7, 'sub_type_level_2' => 21, 'created_by' => 1, 'code' => 4110, 'name' => 'Stationaries'],
            ['type' => 5, 'sub_type' => 7, 'sub_type_level_2' => 21, 'created_by' => 1, 'code' => 4120, 'name' => 'Subscription and Licenses'],
            ['type' => 5, 'sub_type' => 7, 'sub_type_level_2' => 21, 'created_by' => 1, 'code' => 4130, 'name' => 'Tender'],
            ['type' => 5, 'sub_type' => 7, 'sub_type_level_2' => 21, 'created_by' => 1, 'code' => 4140, 'name' => 'Transportation Expenses'],
            ['type' => 5, 'sub_type' => 7, 'sub_type_level_2' => 21, 'created_by' => 1, 'code' => 4150, 'name' => 'Vehicle Maintenance'],
            ['type' => 5, 'sub_type' => 7, 'sub_type_level_2' => 21, 'created_by' => 1, 'code' => 4160, 'name' => 'Xmas Package'],
            ['type' => 5, 'sub_type' => 7, 'sub_type_level_2' => 21, 'created_by' => 1, 'code' => 4170, 'name' => 'Other Admin Expenses'],



        ];

        ChartOfAccount::insert($data);
    }
}
