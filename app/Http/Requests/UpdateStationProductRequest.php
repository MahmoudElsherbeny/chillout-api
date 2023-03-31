<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Traits\ApiResponseTrait;

class UpdateStationProductRequest extends FormRequest
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
            'storage_num' => 'required|numeric|gt:0|lt:20',
            'storage_capacity' => 'required||numeric|gt:1000|lt:1000000'
        ];
    }

    public function messages()
    {
        return [
            'storage_num.required' => 'عدد خزانات المتج مطلوب',
            'storage_num.numeric' => 'عدد خزانات المتج يجب ان يكون رقم',
            'storage_num.gt' => 'عدد خزانات المتج اقل من العدد المسموح',
            'storage_num.lt' => 'عدد خزانات المتج اكبر من العدد المسموح',
            'storage_capacity.required' => 'سعة الخزانات مطلوبة',
            'storage_capacity.numeric' => 'سعة الخزانات يجب ان تكون رقم',
            'storage_capacity.gt' => 'سعة الخزانات اقل من العدد المسموح',
            'storage_capacity.lt' => 'سعة الخزانات اكبر من العدد المسموح',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->apiResponse($validator->errors(), 401, 'Validation errors'));
    }
}
