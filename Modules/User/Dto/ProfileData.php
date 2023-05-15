<?php

namespace Modules\User\Dto;

use Spatie\DataTransferObject\DataTransferObject;

class ProfileData extends DataTransferObject
{

    public ?string $last_name;

    public ?string $first_name;

}
