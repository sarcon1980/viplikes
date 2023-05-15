<?php

namespace Modules\Service\Rules;

use Modules\Service\Models\Service;
use Modules\Service\Models\ServiceItem;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckedPackageItemRule implements ValidationRule
{
    protected $item;

    public function __construct($item)
    {
        $this->item = $item;
    }

    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $service = Service::query()->find($this->item->service_id);

        if ($service->type == Service::TYPE_PACKAGE) {

            if (!$value)
                $fail('Состав пакета обязателен для заполнения!');

            if (count($value) != ServiceItem::COUNT_PACKAGE_ITEMS)
                $fail('Необходимо выбрать 3 позиции');


            $ids = array_column($value, 'id');
            $uniqueIds = array_unique($ids);

            $isUnique = count($ids) === count($uniqueIds);

            if (!$isUnique) {
                $fail('Позиции должны быть разные');
            }
        }
    }

}
