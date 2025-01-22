<?php

namespace App\Http\Requests;

use App\Models\EquipmentAndFee;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEquipmentAndFeeRequest extends FormRequest
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
        $rules = EquipmentAndFee::$rules;

        return $rules;
    }
}
