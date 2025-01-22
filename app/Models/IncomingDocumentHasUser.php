<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

 class IncomingDocumentHasUser extends Model
{
    use SoftDeletes;
    use HasFactory;
    public $table = 'incoming_documents_has_users';

    public $fillable = [
        'document_id',
        'user_id',
        'assigned_by',
        'start_date',
        'end_date',
        'is_download',
        'allow_share',
    ];

    protected $casts = [
        'document_id' => 'integer',
        'user_id' => 'integer',
        'assigned_by' => 'integer'
    ];

    public static array $rules = [
        'document_id' => 'required',
        'user_id' => 'required',
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

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }
}
