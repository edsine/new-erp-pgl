<?php

namespace Modules\Leaves\Http\Requests;

use Modules\Leaves\Models\leaves;
use Illuminate\Foundation\Http\FormRequest;

class CreateleavesRequest extends FormRequest
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
        return leaves::$rules;
    }
}
