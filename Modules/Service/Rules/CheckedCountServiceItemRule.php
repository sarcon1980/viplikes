<?php

namespace Modules\Service\Rules;

use Modules\Service\Models\Service;
use Modules\Service\Models\ServiceItem;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckedCountServiceItemRule implements ValidationRule
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

        // dd($attribute, $value ,$this->item->service_id, $service->type );

        if ($service->type == Service::TYPE_PRODUCT) {

            if (!$value)
                $fail('Количество обязательно для заполнения');

            $isExistCount = ServiceItem::query()
                ->where('service_id', $this->item->service_id)
                ->where('type', $this->item->type)
                ->where('count', $value)
                ->where('id', '!=', $this->item->id)
                ->count();

            if ($isExistCount)
                $fail('В данном сервисе уже есть такое количество и таким типом');
        }
    }
}
