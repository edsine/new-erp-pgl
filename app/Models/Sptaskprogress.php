<?php

namespace App\Models;

use App\Models\Sptask;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sptaskprogress extends Model
{
    use HasFactory;

    public $fillable=['name'];
    public function sptask(){
        return $this->hasMany(Sptask::class,'progress','id');
    }
}
