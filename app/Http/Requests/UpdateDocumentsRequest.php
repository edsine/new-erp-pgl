<?php

namespace App\Http\Requests;

use App\Models\Documents;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDocumentsRequest extends FormRequest
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
        /* $rules = Documents::$rules;
        $id = $this->route('document');
        $rules['title'] = 'required|unique:documents,title,' . $id;
        return $rules; */
        return [
           
           /*  'title' => 'required|string',
            'description' => 'required|string',
            'file' => 'nullable', */
            
        ];
    }
}
