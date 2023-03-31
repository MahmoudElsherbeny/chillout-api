<?php

namespace App\Http\Requests;

use App\Traits\ApiResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SearchByDateRequest extends FormRequest
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
            'from' => 'required|date_format:Y-m-d',
            'to' => 'required|date_format:Y-m-d|after_or_equal:from',
        ];
    }

    public function messages()
    {
        return [
            'from.required' => 'بداية تاريخ البحث مطلوب',
            'from.date_format' => 'صيغة التاريخ يجب ان تكون Y-m-d',
            'to.required' => 'نهاية تاريخ البحث مطلوب',
            'to.date_format' => 'صيغة التاريخ يجب ان تكون Y-m-d',
            'to.after_or_equal' => 'الفترة المحددة بتاريخ البحث غير صحيحة',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->apiResponse($validator->errors(), 401, 'Validation errors'));
    }
}
