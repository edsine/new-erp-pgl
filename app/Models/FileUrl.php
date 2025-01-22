<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileUrl extends Model
{
    use HasFactory;

    protected $fillable = ['random_code', 'actual_url'];
}

