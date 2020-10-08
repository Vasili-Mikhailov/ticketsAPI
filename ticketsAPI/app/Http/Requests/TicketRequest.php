<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TicketRequest extends FormRequest
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
            'title' => 'required|max:50',
            'text' => 'required|max:200',
            'author_name' => 'required',
            'author_tel' => 'required|numeric',
            'file' => 'max:3'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 400));
    }

    public function messages()
    {
        return [
            'title.required' => 'The title field is required!',
            'text.required' => 'The text field is required!',
            'author_name.required' => 'The author_name field is required!',
            'author_tel.required' => 'The author_tel field is required!',
            'title.max' => 'Maximum title size is 50 characters!',
            'text.max' => 'Maximum text size is 200 characters!',
            'author_tel.numeric' => 'author_tel should be numeric!',
            'file.max' => 'Maximum 3 files!',
        ];
    }
}
