<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreBookRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'author' => 'required',
            'title' => [
                'required',
                Rule::unique('books')->where(function ($query) {
                    return $query->where('author', $this->author);
                }),
            ],
            'cover' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'title.unique' => 'The combination of author and title must be unique.',
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        // Get the first error message from the validator
        $message = $validator->errors()->first();
        
        // Create a structured response
        $response = [
            'status' => 422,
            'error' => [
                'message' => $message,
                'errors' => $validator->errors(),
            ],
        ];

        throw new HttpResponseException(response()->json($response, 422));
    }
}
