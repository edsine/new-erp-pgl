<?php

namespace App\Repositories;

use App\Models\DocumentsCategory;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class DocumentsCategoryRepository
 * @package App\Repositories
*/

class DocumentsCategoryRepository extends BaseRepository
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
        return DocumentsCategory::class;
    }

    
}
