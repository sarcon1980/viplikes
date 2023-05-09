<?php

namespace Modules\Service\Dto;

use Spatie\DataTransferObject\DataTransferObject;

class ServiceItemData extends DataTransferObject
{
    public int $is_active;

    public ?string $type;

    public int $service_id;

    public ?int $count;

    public float $price;

    public float $price_for_sale;

    public float $discount;

    public ?array $name;
}
