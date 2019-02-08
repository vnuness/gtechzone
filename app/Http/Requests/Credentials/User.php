<?php

namespace App\Http\Requests\Credentials;

use Illuminate\Foundation\Http\FormRequest;

class User extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        switch ($this->method()) {
            case 'PUT':
            case 'PATCH': {
                return $this->user()->can('credentials.users.edit');
            }
            default:
                return $this->user()->can('credentials.users.create');
        }

    }

    /**p
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'PUT':
            case 'PATCH': {
                return [
                    'name' => 'required|string|max:100',
                    'email' => "required|string|email|max:100|unique:users,email,{$this->route('user')}",
                    'roles' => 'required|array',
                ];
            }
            default:
                return [
                    'name' => 'required|string|max:255',
                    'email' => 'required|string|email|max:255|unique:users',
                    'roles' => 'required|array',
                ];
        }
    }
}
