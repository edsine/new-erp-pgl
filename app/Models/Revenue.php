<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Revenue extends Model
{
    protected $fillable = [
        'date',
        'amount',
        'account_id',
        'customer_id',
        'category_id',
        'recurring',
        'payment_method',
        'reference',
        'description',
        'created_by',
        'revenue_type',
        'expense_head_debit',
        'expense_head_credit',
        'project_id'
    ];

    public function category()
    {
        return $this->hasOne('App\Models\ProductServiceCategory', 'id', 'category_id');
    }

    public function expenseHeadDebit()
    {
        return $this->hasOne('App\Models\ChartOfAccount', 'id', 'expense_head_debit');
    }

    public function expenseHeadCredit()
    {
        return $this->hasOne('App\Models\ChartOfAccount', 'id', 'expense_head_credit');
    }

    public function customer()
    {
        return $this->hasOne('App\Models\Customer', 'id', 'customer_id');
    }

    public function project()
    {
        return $this->hasOne('App\Models\Project', 'id', 'project_id');
    }

    public function bankAccount()
    {
        return $this->hasOne('App\Models\BankAccount', 'id', 'account_id');
    }
}
