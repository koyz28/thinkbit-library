<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateBookRequest extends FormRequest
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
        $bookId = $this->route('id');
        return [
            'author' => 'required',
            'title' => [
                'required',
                Rule::unique('books')->where(function ($query) use ($bookId) {
                    return $query->where('author', $this->author)
                                 ->where('id', '!=', $bookId); // Exclude the current record
                }),
            ],
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
        foreach($validator->errors()->messages() as $error){
            $message = $error[0];
            break;
        }
        throw new HttpResponseException(response()->json([
            'message' => $message,
            'status' => 422
        ]));
    }
}
