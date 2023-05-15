<?php

namespace Modules\User\Repository;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Core\Repository\BaseRepository;
use Modules\User\Models\User;
use Spatie\QueryBuilder\QueryBuilder;

class UserRepository extends BaseRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    public function search(): \Illuminate\Pagination\LengthAwarePaginator
    {
        return QueryBuilder::for($this->model)
            ->withTrashed()
            ->allowedFilters([
            ])
            ->allowedSorts([
            ])
            ->defaultSort('-id')
            ->paginate(15);
    }

    public function create(array $userData): User
    {
        $user = $this->model;

        DB::beginTransaction();
        try {
            $userData['password'] = Hash::make($userData['password']);
            $user->fill($userData);

            if (!$user->save()) {
                throw new Exception('Can`t save model');
            }

            $user->profile()->create($userData['profile']);

            if ($userData['roles']) {
                $user->assignRole(array_column($userData['roles'], 'name'));
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return $user;

    }

    public function update(int $id, array $userData): User
    {
        $user = $this->find($id);


        DB::beginTransaction();
        try {
            $userData['password'] = $userData['password'] != null ? Hash::make($userData['password']) : $user->password;
            $user->fill($userData);

            if (!$user->save()) {
                throw new Exception('Can`t save model');
            }

            $user->profile()->update($userData['profile']);


            if ($userData['roles']) {
                $user->syncRoles(array_column($userData['roles'], 'name'));

            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return $user;
    }
}
