<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Auditable as AuditingAuditable;
use OwenIt\Auditing\Contracts\Auditable;

class IncomingDocumentComment extends Model implements Auditable
{
    use SoftDeletes;
    use AuditingAuditable;
    use HasFactory;
    public $table = 'incoming_documents_comments';

    public $fillable = [
        'created_by',
        'document_id',
        'comment',
        'status',
        'approved_date_time',
    ];

    

    public function createdBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by', 'id');
    }

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\IncomingDocumentsCategory::class, 'category_id', 'id');
    }

}
