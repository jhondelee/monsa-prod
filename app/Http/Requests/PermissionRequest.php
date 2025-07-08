<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PermissionRequest extends FormRequest
{
    /**
     *  Extends Rules
     *  
     *  @return bool
     */
    public function __construct()
    {
        \Validator::extend('routeexists', function($attribute, $value, $parameters, $validator) {
            if (\Route::has($value)) {
                return true;
            }            
            return false;
        });        
    }
    
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
            'route_name' => 'required|routeexists:'.$this->route_name.'|unique:permissions,route_name',
        ];
        
        if (\Route::currentRouteName() == 'permission.update') {
           $rules['route_name'] .= ','. $this->id;
        }        
        
        return $rules;
    }
    
    public function messages()
    {
        return [
            'error' => 'Invalid Route Name.'
        ];
    }
}
