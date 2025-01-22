<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

 class DocumentHasRole extends Model 
{
    use SoftDeletes;
    use HasFactory;
    public $table = 'documents_has_roles';

    public $fillable = [
        'role_id',
        'user_id',
        'document_id',
        'assigned_by',
        'start_date',
        'end_date',
        'is_download',
    ];

    protected $casts = [
        'role_id' => 'integer',
        'document_id' => 'integer',
        'assigned_by' => 'integer'
    ];

    public static array $rules = [
        'role_id' => 'required',
        'document_id' => 'required',
        'assigned_by' => 'required'
    ];

    public function assignedBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'assigned_by', 'id');
    }

    public function document(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Documents::class, 'document_id', 'id');
    }

    public function role(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Role::class, 'role_id', 'id');
    }

}
