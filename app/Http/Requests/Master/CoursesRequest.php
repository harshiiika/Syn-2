<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class CoursesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // set to auth/permission check if needed
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
         $id = $this->route('course'); // may be an id or model depending on binding

        $uniqueCodeRule = 'unique:courses,course_code';
        if ($id) {
            // allow same course_code on update
            $uniqueCodeRule .= ',' . $id . ',_id'; // with Mongo you may need to test unique rule behavior
        }

        return [
            'serial_no'   => 'required|numeric',
            'course_name' => 'required|string|max:255',
            'course_type' => 'required|string|max:100',
            'class_name'  => 'required|string|max:100',
            'course_code' => ['required','string','max:50',$uniqueCodeRule],
            'status'      => 'required|in:active,inactive',
        ];
    }
}

   