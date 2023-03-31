<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Traits\ApiResponseTrait;

class UpdateUserRequest extends FormRequest
{
    use ApiResponseTrait;
    
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
        return [
            'name' => 'required|max:150|unique:users,name,'.$this->id,
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'اسم المستخدم مطلوب',
            'name.max' => 'اسم المستخدم اطول من الازم',
            'name.unique' => 'اسم المستخدم مستخدم من قبل',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->apiResponse($validator->errors(), 401, 'Validation errors'));
    }
}
