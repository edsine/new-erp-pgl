<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DucumentUpload extends Model
{
    protected $fillable = [
        'name',
        'role',
        'document',
        'description',
        'created_by',
        'department_id',
        'user_id',
    ];

    // protected $casts = [
    //     'role' => 'array',
    // ];

    public function user(){
        return $this->belongsTo('App\Models\User','user_id','id');
    }

    public function department(){
        return $this->belongsTo('App\Models\Department','department_id','id');
    }


    
    
}
