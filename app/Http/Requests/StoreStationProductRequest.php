<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use App\Traits\ApiResponseTrait;

class StoreStationProductRequest extends FormRequest
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
            'petrol_type' => [
                'required',
                Rule::unique('stations_petrol_types', 'petrol_type')->where(function($query) {
                    return $query->where('user_id', $this->id);
                })
            ],
            'storage_num' => 'required|numeric|gt:0|lt:20',
            'storage_capacity' => 'required||numeric|gt:1000|lt:1000000'
        ];
    }

    public function messages()
    {
        return [
            'petrol_type.required' => 'نوع المتج مطلوب',
            'petrol_type.unique' => 'نوع المتج مضاف للمحطة من قبل',
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
