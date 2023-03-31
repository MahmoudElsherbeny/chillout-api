<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Traits\ApiResponseTrait;

class StoreUserRequest extends FormRequest
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
            'name' => 'required|max:150|unique:users,name',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:9',
            'user_type' => 'required|numeric',
            'petrol_types' => 'required_if:user_type,0|array',
            'petrol_types.*.type' => 'required_if:user_type,0|distinct',
            'petrol_types.*.storage_num' => 'required_if:user_type,0|numeric|gt:0|lt:20',
            'petrol_types.*.storage_capacity' => 'required_if:user_type,0|numeric|gt:1000|lt:1000000'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'اسم المستخدم مطلوب',
            'name.max' => 'اسم المستخدم اطول من الازم',
            'name.unique' => 'اسم المستخدم مستخدم من قبل',
            'email.required' => 'البريد الالكترونى مطلوب',
            'email.email' => 'البريد الالكترونى يجب ان يكون بصيغة صحيحة',
            'email.unique' => 'البريد الالكترونى مسجل من قبل',
            'password.required' => 'الباسورد مطلوب',
            'password.min' => 'الباسورد يجب ان يكون اكتر من 9 خانات',
            'user_type.required' => 'نوع المستخدم مطلوب',
            'user_type.numeric' => 'نوع المستخدم يجب ان يكون رقم',
            'petrol_types.required_if' => 'بيانات المتجات البترولية مطلوبة فى حالة كون المستخدم محطة',
            'petrol_types.*.type.required_if' => 'نوع المتج مطلوب فى حالة كون المستخدم محطة',
            'petrol_types.*.type.distinct' => 'نوع المنتج مكرر',
            'petrol_types.*.storage_num.required_if' => 'عدد خزانات المتج مطلوب فى حالة كون المستخدم محطة',
            'petrol_types.*.storage_num.numeric' => 'عدد خزانات المتج يجب ان يكون رقم',
            'petrol_types.*.storage_num.gt' => 'عدد خزانات المتج اقل من العدد المسموح',
            'petrol_types.*.storage_num.lt' => 'عدد خزانات المتج اكبر من العدد المسموح',
            'petrol_types.*.storage_capacity.required_if' => 'سعة الخزانات مطلوبة فى حالة كون المستخدم محطة',
            'petrol_types.*.storage_capacity.numeric' => 'سعة الخزانات يجب ان تكون رقم',
            'petrol_types.*.storage_capacity.gt' => 'سعة الخزانات اقل من العدد المسموح',
            'petrol_types.*.storage_capacity.lt' => 'سعة الخزانات اكبر من العدد المسموح',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->apiResponse($validator->errors(), 401, 'Validation errors'));
    }
}
