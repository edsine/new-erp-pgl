<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Priority extends Model
{
    use HasFactory;

    // Define the table associated with the model
    protected $table = 'priorities';

    // Define which fields are mass assignable
    protected $fillable = [
        'name',
        'description',
        'example_usage',
        'action_required',
        'color_code',
    ];

    // Optional: You can define a cast if you need specific formatting
    // protected $casts = [
    //     'created_at' => 'datetime',
    //     'updated_at' => 'datetime',
    // ];

    // Define the relationship between Priority and DocumentHasUser
    public function documentsHasUsers()
    {
        return $this->hasMany(DocumentHasUser::class);
    }
}
