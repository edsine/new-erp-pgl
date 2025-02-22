<?php

namespace App\Http\Requests;

use App\Models\ServiceApplication;
use Illuminate\Foundation\Http\FormRequest;

class CreateServiceApplicationRequest extends FormRequest
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
        return ServiceApplication::$rules;
    }
}
