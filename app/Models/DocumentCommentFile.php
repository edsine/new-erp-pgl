<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Auditable as AuditingAuditable;
use OwenIt\Auditing\Contracts\Auditable;

class DocumentCommentFile extends Model implements Auditable
{
    use SoftDeletes;
    use AuditingAuditable;
    use HasFactory;
    public $table = 'documents_comments_files';

    public $fillable = [
        'created_by',
        'document_id',
        'comment',
    ];

    

    public function createdBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by', 'id');
    }

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\DocumentsCategory::class, 'category_id', 'id');
    }

    public function assignedUsers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\DocumentManager\Models\MemoHasUser::class);
    }

    public function assignedRoles(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\DocumentManager\Models\MemoHasDepartment::class);
    }
}
