<?php

namespace App\Repositories;

use App\Models\DocumentHasRole;
use App\Repositories\BaseRepository;

class DocumentHasRoleRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'role_id',
        'document_id',
        'assigned_by'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return DocumentHasRole::class;
    }


}
