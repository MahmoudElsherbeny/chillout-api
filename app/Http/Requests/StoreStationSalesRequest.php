<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiResponseTrait;
use Illuminate\Validation\Rule;

class StoreStationSalesRequest extends FormRequest
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
            'sales' => 'required',
            'sales.*.type' => [
                'required',
                'distinct',
                Rule::exists('stations_petrol_types', 'petrol_type')->where(function($query) {
                    return $query->where('user_id', Auth::user()->id);
                })
            ],
            'sales.*.value' => 'required|numeric|gte:0',
            'created_by' => 'required|max:150|min:10',
        ];
    }

    public function messages()
    {
        return [
            'sales.required' => 'تمام المبيعات مطلوب',
            'sales.*.type.required' => 'نوع المنتج مطلوب',
            'sales.*.type.distinct' => 'نوع المنتج مكرر',
            'sales.*.type.exists' => 'نوع المنتج غير مسجل للمحطة',
            'sales.*.value.required' => 'تمام المبيعات مطلوب',
            'sales.*.value.numeric' => 'تمام المبيعات يجب ان يكون ارقام صحيحة',
            'sales.*.value.gte' => 'تمام المبيعات لا يجب ان يكون اقل من الصفر',
            'created_by.required' => 'اسم مدخل التمام مطلوب',
            'created_by.max' => 'اسم مدخل التمام اكبر من الازم',
            'created_by.required' => 'اسم مدخل التمام قصير',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->apiResponse($validator->errors(), 401, 'Validation errors'));
    }
    
}
