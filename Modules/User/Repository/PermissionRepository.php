<?php

namespace Modules\User\Repository;

use Exception;
use Illuminate\Support\Facades\DB;
use Modules\Core\Repository\BaseRepository;
use Modules\User\Models\Permission;

class PermissionRepository extends BaseRepository
{

    public function __construct(Permission $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    public function create(array $data): Permission
    {
        $model = $this->model;

        DB::beginTransaction();
        try {
            $model->fill($data);
            $model->guard_name = 'web';

            if (!$model->save()) {
                throw new Exception('Can`t save model');
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return $model;
    }
}
