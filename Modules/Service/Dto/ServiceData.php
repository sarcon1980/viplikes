<?php

namespace Modules\Service\Dto;

use Spatie\DataTransferObject\DataTransferObject;

class ServiceData extends DataTransferObject
{
    public string $name;

    public ?string $type;

    public ?int $parent_id;

    public int $is_active;

    public ?int $is_bestseller;

    public ?int $is_popular;

    public ?int $is_recommendation;

    public ?string $url;

    public array|string|null $optionsTitle = [];

    public array|string|null $optionsTitleProduct;

    public ?array $optionsAccount = [];

    public ?array $optionsVar = [];
}
