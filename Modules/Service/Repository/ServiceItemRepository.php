<?php

namespace Modules\Service\Repository;

use Modules\Core\Repository\BaseRepository;
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
            ->where('service_id',$service_id)
            ->allowedFilters([
            AllowedFilter::exact('type'),
            ])->allowedSorts([
        ])->defaultSort('position');
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
