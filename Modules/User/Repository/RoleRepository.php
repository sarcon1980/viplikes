<?php

namespace Modules\User\Repository;

use Exception;
use Illuminate\Support\Facades\DB;
use Modules\Core\Repository\BaseRepository;
use Modules\User\Models\Role;
use Spatie\QueryBuilder\QueryBuilder;

class RoleRepository extends BaseRepository
{
    public function __construct(Role $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    public function search(): \Illuminate\Pagination\LengthAwarePaginator
    {
        return QueryBuilder::for($this->model)
            ->with('permissions')
            ->allowedFilters([
            ])
            ->allowedSorts([
            ])
            ->defaultSort('-id')
            ->paginate(15);
    }

    public function create(array $data): Role
    {
        $model = $this->model;

        DB::beginTransaction();
        try {
            $model->fill($data);
            $model->guard_name = 'web';

            if (!$model->save()) {
                throw new Exception('Can`t save model');
            }

            if ($data['permissions']) {
                $model->givePermissionTo(collect($data['permissions'])->pluck('id')->toArray());
                //$model->syncPermissions($data['permissions']);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return $model;
    }


}
