<?php

namespace App\Models;

use App\Models\Documents;
use App\Models\Reminderuser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Reminder extends Model
{
    use HasFactory;

    public $fillable=[
        'subject',
        'message',
        'reminderstart_date',
        'reminderend_date',
        'frequency',
        'documents_manager_id',
        'user_id',
    ];

    public function reminderuser(){
        return $this->hasMany(Reminderuser::class);
    }
    public function documentmanager(){
        return $this->belongsTo(Documents::class,'documents_manager_id');
    }
}
