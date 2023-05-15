<?php

namespace Modules\Service\Repository;

use Illuminate\Support\Facades\DB;
use Modules\Core\Repository\BaseRepository;
use Modules\Service\Models\Service;
use Modules\Service\Models\ServiceItem;
use Illuminate\Database\Eloquent\Collection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ServiceItemRepository extends BaseRepository
{
    public function __construct(ServiceItem $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    public function updatePosition($data)
    {
        foreach ($data as $k => $item) {
            ServiceItem::where('id', $item)->update(['position' => $k]);
        }
    }

    public function searchItems(int $service_id)
    {
        return QueryBuilder::for($this->model)
            //->with('itemsPackage')
            ->where('service_id', $service_id)
            ->allowedFilters([
                AllowedFilter::exact('type'),
            ])->allowedSorts([
            ])->defaultSort('position');
    }

    public function create(array $data): ServiceItem
    {
        $model = $this->model;

        DB::beginTransaction();
        try {
            $model->fill($data);
            if (!$model->save()) {
                throw new \Exception('Can`t save model');
            }

            if ($model->service->type == Service::TYPE_PACKAGE && $data['package_items']) {
               $model->itemsPackage()->attach(array_column($data['package_items'], 'id'));
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return $model;
    }

    public function update(int $id, array $data): ServiceItem
    {
        $model = $this->find($id);

        DB::beginTransaction();
        try {
            $model->fill($data);
            if (!$model->save()) {
                throw new \Exception('Can`t save model');
            }

            //dd(  array_column($data['package_items'] ,'id') );
            if ($model->service->type == Service::TYPE_PACKAGE && $data['package_items']) {
                $model->itemsPackage()->sync(array_column($data['package_items'], 'id'));
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return $model;
    }

    public function getItemsParentWithoutPackage(int $parent_id): ?array
    {
        $items = Service::query()
            ->where('parent_id', $parent_id)
            ->whereNot('type', Service::TYPE_PACKAGE)
            ->with('items')
            ->get();

        return $this->prepareItemsForList($items->toArray());
    }

    private function prepareItemsForList($services): ?array
    {
        $prepare = [];
        foreach ($services as $service) {
            $items = [];
            $name = $service['name'];

            foreach ($service["items"] as $item) {
                $items[] = array(
                    "name" => $item['count'] . ' ' . $service['options']['title'] . ' - ' . $item['type'],
                    "id" => $item['id']
                );
            }

            $prepare[] = array(
                "name" => $name,
                "items" => $items
            );
        }

        return $prepare;
    }

    public function all(): Collection
    {
        return $this->model->query()->with(['service'])->get();
    }

    public function find($id): ?ServiceItem
    {
        return $this->model->query()->with(['service'])->find($id);
    }

}
