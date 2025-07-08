<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        $rules =  [
            'role_id'=> 'required',
            'emp_number' => 'required|max:20|unique:employees,emp_number',
            'firstname' => 'required|max:50',
            'lastname' => 'required|max:50',
            'middlename' => 'required|max:50',
            'department' => 'required|max:50',
            'position' => 'required|max:50',
            
            'role_id' => 'required|integer',
        
            'email'    => 'required|email|unique:users,email',
            'username' => 'required|min:3|max:40|unique:users,username',
            'password' => 'required|min:6|confirmed',
        ];
        
        if (\Route::currentRouteName() == 'user.update') {

            $employee = \App\Employee::select('id')->whereUserId($this->id)->first();
            
            $rules['emp_number'] .= ','. $employee->id;
            
            $rules['email'] .= ','. $this->id;
            $rules['username'] .= ','. $this->id;
            
            unset($rules['password']);
        }
        
        return $rules;
    }
    
    public function messages()
    {
        return [
            'parent_id.required' => 'The assign field is required.',
            'parent_id.unique' => 'The employer number has already been taken.'
        ];
    }
}
