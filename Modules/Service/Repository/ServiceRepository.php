<?php

namespace Modules\Service\Repository;

use Modules\Core\Repository\BaseRepository;
use Modules\Service\Models\Service;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ServiceRepository extends BaseRepository
{
    private $options = [];

    public function __construct(Service $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    public function create(array $data): Model
    {
        $model = $this->model;

        DB::beginTransaction();
        try {

            $data['position'] = $this->getLastPosition($data['parent_id']);

            $model->fill($data);

            if (!$model->save()) {
                throw new \Exception('Can`t save model');
            }

            $this->saveOptions($model, $data);

            DB::commit();
        } catch (\Exception $e) {
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
                throw new \Exception('Can`t save model');
            }

            $this->saveOptions($model, $data, 'update');

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return $model;
    }

    private function saveOptions(Service $model, array $data, $method = 'create'): void
    {
        if ($model->isProductType()) {
            $this->options['title'] = $data['optionsTitleProduct'];
        }

        if ($data['optionsAccount'])
            $this->options['accounts'] = json_encode($data['optionsAccount']);

        if ($data['optionsVar'])
            $this->options['types'] = json_encode($data['optionsVar']);

        //   dd($this->options);

        if ($this->options) {

            if ($method == 'create') {
                if (!$model->options()->create($this->options)) {
                    throw new \Exception('Can`t save model options');
                }
            } elseif ($method == 'update') {
                if (!$model->options()->update($this->options)) {
                    throw new \Exception('Can`t save model options');
                }
            }
        }
    }

    public function getLastPosition(int $parent_id = 0): int
    {
        return $this->model->query()->where('parent_id', '=', $parent_id)->max('position') + 1;
    }

    public function all(): Collection
    {
        return $this->model->query()->with(['items', 'options', 'children'])->get();
    }

    public function root(): Collection
    {
        return $this->model->query()->with(['items', 'options', 'children'])->where('parent_id', '=', 0)->get();
    }

    public function find($id): ?Model
    {
        return $this->model->query()->with(['items', 'options', 'children'])->find($id);
    }

}
