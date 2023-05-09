<?php

namespace Modules\Core\Repository;

use Modules\Core\Repository\Interfaces\EloquentRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

class BaseRepository implements EloquentRepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function search(): \Illuminate\Pagination\LengthAwarePaginator
    {
        return QueryBuilder::for($this->model)
            ->allowedFilters([
            ])
            ->allowedSorts([
            ])
            ->defaultSort('-id')
            ->paginate(15);
    }

    public function all(): Collection
    {
        return $this->model->get();
    }

    /**
     * @param array $attributes
     *
     * @return Model
     */
    public function create(array $data): Model
    {
        $model = $this->model;

        DB::beginTransaction();
        try {
            $model->fill($data);
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

    public function update(int $id, array $data): Model
    {
        $model = $this->find($id);

        DB::beginTransaction();
        try {
            $model->fill($data);
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


    /**
     * @param int $id
     * @return bool
     * @throws Exception|\Throwable
     */
    public function destroy(int $id): bool
    {
        $model = $this->find($id);
        DB::beginTransaction();
        try {
            if (!$model->delete()) {
                throw new Exception('Can`t delete model');
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return true;
    }

    /**
     * @param $id
     * @return Model
     */
    public function find($id): ?Model
    {
        return $this->model->find($id);
    }

}
