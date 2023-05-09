<?php

namespace Modules\Service\Http\Requests\Admin;

use Modules\Service\Rules\CheckedCountServiceItemRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateServiceItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'is_active' => 'int',

            'type' => [
                'string',
                'nullable',
            ],

            'name' =>   [ ],

            'name.*.count' => 'required|int',

            'service_id' => [
                'int',
                'required'
            ],

            'url' => [
                'string',
                'nullable'
            ],

            'count' => [
                resolve(CheckedCountServiceItemRule::class, ['item' => $this]),
                'int',
            ],

            'price' => [
                'numeric',
                'min:0',
                'required',
                //resolve(CheckedCountWithPriceServiceItemRule::class, ['item' => $this]),
            ],

            'price_for_sale' => [
                'numeric',
                'min:0',
                'required'
            ],

            'discount' => [
                'numeric',
                'min:0',
                'required'
            ],
        ];
    }
}
