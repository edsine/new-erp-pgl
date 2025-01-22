<?php

namespace App\Models;

use Users;
use App\Models\User;
use App\Models\Reminder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reminderuser extends Model
{
    use HasFactory;

    public $fillable=[
        'reminder_id',
        'user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function reminder(){
        return $this->belongsTo(Reminder::class,'reminder_id');
    }
}
