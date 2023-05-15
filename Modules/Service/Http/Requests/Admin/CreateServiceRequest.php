<?php

namespace Modules\Service\Http\Requests\Admin;

use Modules\Service\Models\Service;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateServiceRequest extends FormRequest
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
            'name' => 'required|string|max:100',
            'is_active' => 'int',
            'is_bestseller' => 'int',
            'is_popular' => 'int',
            'is_recommendation' => 'int',
            'type' => [
                Rule::when($this->parent_id != 0, 'required'),
                'string',
                'nullable',
            ],
            'parent_id' => [
                'int',
                'nullable',
                Rule::requiredIf($this->type != null),

                ],
            'url' => [
                'string',
                'nullable'
            ],
//            'optionsTitle' =>
//                [
//                    Rule::requiredIf($this->type == Service::TYPE_PACKAGE),
//                    Rule::when($this->type == Service::TYPE_PACKAGE, 'max:3|min:3|array'),
//                ],
//            'optionsTitle.*.text' => 'required|string',

            'optionsTitleProduct' =>
                [
                    Rule::when($this->type == Service::TYPE_PRODUCT, 'required|string')
                ],
            'optionsAccount' =>
                [
                    Rule::requiredIf($this->type != null),
                    'array'
                ],
            'optionsAccount.*.text' => 'required|string',
            'optionsVar' =>
                [
                    'array'
                ],
            'optionsVar.*.text' => 'required|string'
        ];
    }
}
