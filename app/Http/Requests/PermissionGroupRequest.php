<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PermissionGroupRequest extends FormRequest
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
            'name' => 'required|unique:permission_group,name',
            'icon_class' => 'required',
        ];
        
        if (\Route::currentRouteName() == 'pgroup.update') {
            $rules['name'] .= ','. $this->id;
        }        
        
        return $rules;
    }
}
