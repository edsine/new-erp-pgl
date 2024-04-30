<?php

namespace App\Models;

use App\Models\User;
use App\Models\Sptaskusers;
use App\Models\Sptaskprogress;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sptask extends Model
{
    use HasFactory;

    public $fillable=[

        'title',
        'description',
        'start_date',
        'end_date',
        'status',
        'tags',
        'created_by',
        'progress'
    ];


    public function taskprogress(){
        return $this->belongsTo(Sptaskprogress::class,'progress','id');
    }
    public function user(){
        return $this->belongsTo(User::class,'created_by','id');
    }

    public function sptaskuser(){
        return $this->hasMany(Sptaskusers::class,'sptask_id','id');
    }
}
