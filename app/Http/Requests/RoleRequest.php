<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
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
        $rules = [
            'name' => 'required|min:4|max:49|alpha_dash|unique:roles,name',
            'display_name' => 'required|min:4|max:90'
        ];
        
        if (\Route::currentRouteName() == 'role.update') {
            $rules['name'] .= ','. $this->id;
        }
        
        return $rules;
    }
}
