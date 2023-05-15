<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\User\Dto\ProfileData;
use Modules\User\Dto\UserData;

class CreateUserRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => [
                'unique' => 'unique:users',
                'string' => 'string',
                'between' => 'between:3,20',
                'required' => 'required'
            ],
            'email' => ['email' => 'email', 'required' => 'required','unique' => 'unique:users', 'string' => 'string', 'max' => 'max:255'],
            'password' => 'string|between:6,150|same:confirmPassword|required',

            'last_name' => ['nullable' => 'nullable', 'string' => 'string', 'between' => 'between:1,150', 'regex' => 'regex:/^[\s\p{L}-]+$/u'],
            'first_name' => ['nullable' => 'nullable', 'string' => 'string', 'between' => 'between:1,150', 'regex' => 'regex:/^[\s\p{L}-]+$/u'],


            'roles' => 'array|required',
//            'roles.*' => [Rule::in($possibleRoleIds)],
        ];
    }

    public function getDto(): UserData
    {
        $userData = new UserData($this->validated());
        $userData->profile = new ProfileData($this->validated());

        return $userData;
    }
}
