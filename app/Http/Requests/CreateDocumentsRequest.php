<?php

namespace App\Http\Requests;

use App\Models\Documents;
use Illuminate\Foundation\Http\FormRequest;

class CreateDocumentsRequest extends FormRequest
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
        //return [
            // Add validation for the roles field
           // 'roles' => 'required|array|min:1', // At least one role must be selected
           // 'users' => 'required|array|min:1', // At least one user must be selected
        //];
    }

    /**
     * Customize error messages for validation rules.
     *
     * @return array
     */
    public function messages()
    {
       /*  return [
            'roles.required' => 'Please select at least one role.',
            'users.required' => 'Please select at least one user.',
        ]; */
    }
}
