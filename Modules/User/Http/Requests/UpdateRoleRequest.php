<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $id = $this->request->get('id');

        return [
            'name' => ['required', 'max:25', 'unique:roles,name,' . $id],
            'permissions' => ['required'],
        ];
    }
}
