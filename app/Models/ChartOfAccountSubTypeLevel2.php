<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChartOfAccountSubTypeLevel2 extends Model
{
    use HasFactory;

    protected $table = "chart_of_accounts_sub_type_level_2";

    protected $fillable = [
        'name',
        'sub_type',
        'created_by',
    ];
}
