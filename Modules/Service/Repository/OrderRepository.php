<?php

namespace Modules\Service\Repository;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Core\Repository\BaseRepository;
use Modules\Service\Models\Order;

class OrderRepository extends BaseRepository
{
    public function __construct(Order $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    public function update(int $id, array $data): Order
    {
        $model = $this->find($id);

        DB::beginTransaction();
        try {

            if (! $model->setStatus($data['status'], 'Reason: ' . Str::random(17) )) {
                throw new \Exception('Can`t save model');
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return $model;
    }
}
