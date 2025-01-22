<?php

namespace App\Repositories;

use App\Models\DocumentHasUser;
use App\Repositories\BaseRepository;

class DocumentHasUserRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'document_id',
        'user_id',
        'assigned_by'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return DocumentHasUser::class;
    }

    public function findByUser($user_id)
    {
        $query = $this->model->newQuery();

        return $query->where('user_id', '=', $user_id);
    }

    public function findByUserAndMemo($user_id, $document_id)
    {
        $query = $this->model->newQuery();

        return $query->where('user_id', '=', $user_id)->where('document_id', '=', $document_id)->first();
    }
}
