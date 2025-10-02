<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInquiryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
public function rules(): array
{
    return [
        'name'    => ['required','string','max:100'],
        'email'   => ['required','email'],
        'subject' => ['required','string','max:150'],
        'message' => ['required','string','max:2000'],
        'status'  => ['nullable','in:new,open,closed'],
    ];
}
}
