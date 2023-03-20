<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    protected $fillable = [
        'date',
        'reference',
        'description',
        'journal_id',
        'created_by',
    ];


    public function accounts()
    {
        return $this->hasmany('App\Models\JournalItem', 'journal', 'id');
    }

    public function totalCredit()
    {
        $total = 0;
        foreach ($this->accounts as $account) {
            $total += $account->credit;
        }

        return $total;
    }

    public function totalDebit()
    {
        $total = 0;
        foreach ($this->accounts as $account) {
            // Exclude Bank Account
            if ($account->accounts->code == 100) {
                continue;
            }
            $total += $account->debit;
        }

        return $total;
    }
}
