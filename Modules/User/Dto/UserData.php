<?php

namespace Modules\User\Dto;

use Spatie\DataTransferObject\DataTransferObject;

class UserData extends DataTransferObject
{
    /** @var string|null */
    public $name;

    /** @var string|null */
    public $email;

    /** @var string|null */
    public $password;

    /** @var array|null */
//    public $roles;

    /** @var \Modules\User\Dto\ProfileData|null */
    public $profile;

    public array $roles;
}
