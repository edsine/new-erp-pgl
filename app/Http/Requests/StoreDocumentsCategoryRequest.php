<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreDocumentsCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        // Get the logged-in user's department and branch ID
        $departmentId = Auth::user()->staff->department_id;
        $branchId = Auth::user()->staff->branch_id;

        return [
            'name' => [
                'required',
                'integer',
                Rule::unique('documents_categories')->where(function ($query) use ($departmentId, $branchId) {
                    return $query->where('department_id', $departmentId)
                                 ->where('branch_id', $branchId);
                }),
            ],
            'description' => 'nullable|string',
            'department_id' => 'nullable|integer',
            'branch_id' => 'nullable|integer',
        ];
    }

    /**
     * Get custom error messages for validation.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'The file no. field is required.',
            'name.integer' => 'The file no. must be an integer.',
            'name.unique' => 'The document file no. already exists in your department and branch. Please choose another file no.',
        ];
    }
}
