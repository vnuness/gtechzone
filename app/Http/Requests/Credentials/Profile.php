<?php

namespace App\Http\Requests\Credentials;

use Illuminate\Foundation\Http\FormRequest;

class Profile extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
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
                    'title' => "required|string|max:80|unique:roles,title,{$this->route('profile')}",
                ];
            }
            default:
                return [
                    'title' => 'required|string|max:80|unique:roles',
                ];
        }
    }
}
