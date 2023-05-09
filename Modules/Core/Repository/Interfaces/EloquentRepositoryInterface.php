<?php

namespace Modules\Core\Repository\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Interface EloquentRepositoryInterface
 * @package App\Repositories\Interfaces
 */
interface EloquentRepositoryInterface
{
    public function search(): \Illuminate\Pagination\LengthAwarePaginator;

    public function all(): Collection;

    /**
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes): Model;

    /**
     * @param array $attributes
     * @return Model
     */
    public function update(int $id, array $attributes): Model;

    /**
     * @param $id
     * @return Model
     */
    public function find($id): ?Model;

    public function destroy(int $id): bool;
}
