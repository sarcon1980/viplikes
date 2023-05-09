<?php

namespace Modules\User\Repository;

use Modules\Core\Repository\BaseRepository;
use Modules\User\Models\User;

class UserRepository extends BaseRepository
{

    public function __construct(User $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }
}
