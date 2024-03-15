<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sptaskprogress extends Model
{
    use HasFactory;

    public $fillable=['name'];
    public function sptask(){
        return $this->hasMany(Sptask::class,'progress','id');
    }
}
