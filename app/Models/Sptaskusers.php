<?php

namespace App\Models;

use App\Models\User;
use App\Models\Sptask;
use App\Models\Department;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sptaskusers extends Model
{
    use HasFactory;

    public $fillable=[
        'sptask_id',
        'user_id',
        'invited_by',
        'department_id'
    ];


    public function sptask(){
        return $this->belongsTo(Sptask::class,'sptask_id','id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function department(){
        return $this->belongsTo(Department::class,'department_id','id');
    }

    public function invitedby(){
        return $this->belongsTo(User::class,'invited_by','id');
    }
}
