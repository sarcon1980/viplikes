<?php

namespace Modules\Service\Rules;

use Modules\Service\Models\ServiceItem;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckedCountWithPriceServiceItemRule implements ValidationRule
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
        //dd($attribute, $value ,$this->item->service_id );

        $isExistCount = ServiceItem::query()
            ->where('service_id', $this->item->service_id)
            ->where('type', $this->item->type)
            ->where('price', $this->item->price)
            ->where('count', $value)
            ->count();

        if ($isExistCount)
            $fail('В данном сервисе уже есть такая цена с таким количеством и  типом');
    }
}
