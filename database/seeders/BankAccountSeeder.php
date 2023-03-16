<?php

namespace Database\Seeders;

use App\Models\BankAccount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BankAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $data = [
            ['bank_address' => 'Abuja', 'contact_number' => '0', 'opening_balance' => 0, 'account_number' => '0050928489', 'bank_name' => 'ACCESS BANK', 'holder_name' => 'PGL', 'created_by' => 1],
            ['bank_address' => 'Abuja', 'contact_number' => '0', 'opening_balance' => 0, 'account_number' => '0045540971', 'bank_name' => 'ACCESS BANK', 'holder_name' => 'P2E TECHNOLOGIES', 'created_by' => 1],
            ['bank_address' => 'Abuja', 'contact_number' => '0', 'opening_balance' => 0, 'account_number' => '0043970815', 'bank_name' => 'ACCESS BANK', 'holder_name' => '042 ENGINEERING', 'created_by' => 1],
            ['bank_address' => 'Abuja', 'contact_number' => '0', 'opening_balance' => 0, 'account_number' => '0098531348', 'bank_name' => 'ACCESS BANK', 'holder_name' => 'PIRA-TECH NIG LTD', 'created_by' => 1],
            ['bank_address' => 'Abuja', 'contact_number' => '0', 'opening_balance' => 0, 'account_number' => '0098650544', 'bank_name' => 'ACCESS BANK', 'holder_name' => '247 COMMUNICATIONS', 'created_by' => 1],
            ['bank_address' => 'Abuja', 'contact_number' => '0', 'opening_balance' => 0, 'account_number' => '0023296988', 'bank_name' => 'ACCESS BANK', 'holder_name' => 'PYRICH ENGINEERING', 'created_by' => 1],
            ['bank_address' => 'Abuja', 'contact_number' => '0', 'opening_balance' => 0, 'account_number' => '1012835411', 'bank_name' => 'ZENITH BANK', 'holder_name' => 'PGL', 'created_by' => 1],
            ['bank_address' => 'Abuja', 'contact_number' => '0', 'opening_balance' => 0, 'account_number' => '1015982835', 'bank_name' => 'ZENITH BANK', 'holder_name' => 'P2E TECHNOLOGIES', 'created_by' => 1],
            ['bank_address' => 'Abuja', 'contact_number' => '0', 'opening_balance' => 0, 'account_number' => '1013337273', 'bank_name' => 'ZENITH BANK', 'holder_name' => '042 ENGINEERING', 'created_by' => 1],
            ['bank_address' => 'Abuja', 'contact_number' => '0', 'opening_balance' => 0, 'account_number' => '1017147942', 'bank_name' => 'ZENITH BANK', 'holder_name' => 'PIRA-TECH NIG LTD', 'created_by' => 1],
            ['bank_address' => 'Abuja', 'contact_number' => '0', 'opening_balance' => 0, 'account_number' => '1017323238', 'bank_name' => 'ZENITH BANK', 'holder_name' => 'ELITE STUFF', 'created_by' => 1],
            ['bank_address' => 'Abuja', 'contact_number' => '0', 'opening_balance' => 0, 'account_number' => '1017323252', 'bank_name' => 'ZENITH BANK', 'holder_name' => 'SPARKLE INTERIOR', 'created_by' => 1],
            ['bank_address' => 'Abuja', 'contact_number' => '0', 'opening_balance' => 0, 'account_number' => '1017323221', 'bank_name' => 'ZENITH BANK', 'holder_name' => 'IDLE MINDS', 'created_by' => 1],
            ['bank_address' => 'Abuja', 'contact_number' => '0', 'opening_balance' => 0, 'account_number' => '1017323214', 'bank_name' => 'ZENITH BANK', 'holder_name' => 'EDSINE TECHNOLOGIES', 'created_by' => 1],
            ['bank_address' => 'Abuja', 'contact_number' => '0', 'opening_balance' => 0, 'account_number' => '1024668201', 'bank_name' => 'UBA', 'holder_name' => 'PYRICH GROUP LIMITED (NITT)', 'created_by' => 1],
            ['bank_address' => 'Abuja', 'contact_number' => '0', 'opening_balance' => 0, 'account_number' => '1220125649', 'bank_name' => 'ZENITH BANK', 'holder_name' => 'P2E TECHNOLOGIES (NITT)', 'created_by' => 1],
            ['bank_address' => 'Abuja', 'contact_number' => '0', 'opening_balance' => 0, 'account_number' => '1513355597', 'bank_name' => 'ACCESS BANK', 'holder_name' => 'PIRA-TECH NIG LTD (NITT)', 'created_by' => 1],
            ['bank_address' => 'Abuja', 'contact_number' => '0', 'opening_balance' => 0, 'account_number' => '1016814243', 'bank_name' => 'ZENITH BANK', 'holder_name' => 'ENVEPLAN INTERCONTINENTAL', 'created_by' => 1],
            ['bank_address' => 'Abuja', 'contact_number' => '0', 'opening_balance' => 0, 'account_number' => '0111377177', 'bank_name' => 'GT BANK', 'holder_name' => 'PYRICH IT LTD', 'created_by' => 1],
            ['bank_address' => 'Abuja', 'contact_number' => '0', 'opening_balance' => 0, 'account_number' => '0633313383', 'bank_name' => 'GT BANK', 'holder_name' => 'PYRICH GROUP LTD', 'created_by' => 1],
            ['bank_address' => 'Abuja', 'contact_number' => '0', 'opening_balance' => 0, 'account_number' => '0057884733', 'bank_name' => 'ACCESS BANK', 'holder_name' => '042 PROJECT 2', 'created_by' => 1],
            ['bank_address' => 'Abuja', 'contact_number' => '0', 'opening_balance' => 0, 'account_number' => '0033248094', 'bank_name' => 'ACCESS BANK', 'holder_name' => 'ENPRO CONSULTANTS', 'created_by' => 1],
            ['bank_address' => 'Abuja', 'contact_number' => '0', 'opening_balance' => 0, 'account_number' => '0021996789', 'bank_name' => 'ACCESS BANK', 'holder_name' => 'PYRICH GLOBAL SERVICESS LTS', 'created_by' => 1],
            ['bank_address' => 'Abuja', 'contact_number' => '0', 'opening_balance' => 0, 'account_number' => '325141568759', 'bank_name' => 'BANK OF AMERICA', 'holder_name' => 'PYRICH FOREIGN ACCOUNT', 'created_by' => 1],
            ['bank_address' => 'Abuja', 'contact_number' => '0', 'opening_balance' => 0, 'account_number' => '1223435016', 'bank_name' => 'ZENITH BANK', 'holder_name' => 'NABCO ASSOCIATES LIMITED', 'created_by' => 1],

            ['bank_address' => 'Abuja', 'contact_number' => '0', 'opening_balance' => 0, 'account_number' => '1016106524', 'bank_name' => 'ZENITH BANK', 'holder_name' => 'PYRICH OVERHEAD', 'created_by' => 1],
            ['bank_address' => 'Abuja', 'contact_number' => '0', 'opening_balance' => 0, 'account_number' => '1223435016', 'bank_name' => 'ZENITH BANK', 'holder_name' => 'SPIDER TECH LOGISTICS SERVICES LTD', 'created_by' => 1],
            ['bank_address' => 'Abuja', 'contact_number' => '0', 'opening_balance' => 0, 'account_number' => '1223742160', 'bank_name' => 'ZENITH BANK', 'holder_name' => 'MOUNTAIN HILL PROJECTSLTD', 'created_by' => 1],
            ['bank_address' => 'Abuja', 'contact_number' => '0', 'opening_balance' => 0, 'account_number' => '1025348236', 'bank_name' => 'UBA', 'holder_name' => 'EDSINE TECHNOLOGIES', 'created_by' => 1],
            ['bank_address' => 'Abuja', 'contact_number' => '0', 'opening_balance' => 0, 'account_number' => '1025348243', 'bank_name' => 'UBA', 'holder_name' => 'IDLE MINDS TECHNOLOGIES', 'created_by' => 1],
            ['bank_address' => 'Abuja', 'contact_number' => '0', 'opening_balance' => 0, 'account_number' => '1025348274', 'bank_name' => 'UBA', 'holder_name' => 'ENVEPLAN INTERCONTINENTAL LTD', 'created_by' => 1],
            ['bank_address' => 'Abuja', 'contact_number' => '0', 'opening_balance' => 0, 'account_number' => '0055616418', 'bank_name' => 'UNITY BANK', 'holder_name' => 'EDSINE TECHNOLOGIES LTD', 'created_by' => 1],
            ['bank_address' => 'Abuja', 'contact_number' => '0', 'opening_balance' => 0, 'account_number' => '0055616494', 'bank_name' => 'UNITY BANK', 'holder_name' => 'P2E TECHNOLOGIES LTD', 'created_by' => 1],
            ['bank_address' => 'Abuja', 'contact_number' => '0', 'opening_balance' => 0, 'account_number' => '1100756874', 'bank_name' => 'MUTUAL TRUST', 'holder_name' => 'OMEC INVESTMENT', 'created_by' => 1],
        ];




        BankAccount::insert($data);
    }
}
