<?php

namespace App\Repositories;

use App\Models\Documents;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class DocumentsRepository
 * @package App\Repositories
*/

class DocumentsRepository extends BaseRepository
{
    
    protected $fieldSearchable = [
        'name',
        'description',
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Documents::class;
    }

    
}
