<?php

namespace Modules\User\Dto;

use Spatie\DataTransferObject\DataTransferObject;

class RoleData extends DataTransferObject
{

    public string $name;
    public array $permissions;
}
