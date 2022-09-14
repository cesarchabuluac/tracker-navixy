<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCarRequest extends FormRequest
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
        return [
            'provider_name' => 'required',
            'company' => 'required',
            'vin' => 'required',
            'imei' => 'required',
            'unit_type' => 'required',
            'brand' => 'required',
            'sub_brand' => 'required',
            'model_date' => 'required',
            'zone' => 'required',
            'delegation' => 'required',
            'municipality' => 'required',
            'concession_number' => 'required',
        ];
    }
}
