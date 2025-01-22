<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use Spatie\Permission\Models\Permission;

/**
 * Class UserRepository
 * @package App\Repositories
*/

class PermissionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'guard_name'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model(): string
    {
        return Permission::class;
    }

}
