<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

 class DocumentHasUserFiles extends Model
{
    use SoftDeletes;
    use HasFactory;
    public $table = 'documents_has_users_files';

    public $fillable = [
        'document_id',
        'user_id',
        'assigned_by',
        'start_date',
        'end_date',
        'is_download',
        'allow_share',
        'priority_id',
        'category_id',
    ];

    protected $casts = [
        'document_id' => 'integer',
        'user_id' => 'integer',
        'assigned_by' => 'integer'
    ];

    public static array $rules = [
        'document_id' => 'required',
        'user_id' => 'required',
        'assigned_by' => 'required',
        'category_id' => 'required',
    ];

    // Define the relationship between DocumentHasUserFile and Priority
    public function priority()
    {
        return $this->belongsTo(Priority::class);
    }

    public function assignedBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'assigned_by', 'id');
    }

    public function document(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Documents::class, 'document_id', 'id');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }

    // Define the many-to-many relationship with User
    public function users()
    {
        return $this->belongsToMany(User::class, 'documents_has_users_files', 'document_id', 'user_id');
    }
}
