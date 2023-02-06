<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CelebrationRequest extends FormRequest
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
            'type' => 'required|numeric|exists:types,id',
            'FirstName' => 'required|string',
            'LastName' => 'required|string',
            'Message' => 'required|string',
            'template' => 'required|numeric|exists:templates,id',
            'youtubeCode' => 'string|nullable|size:28',
            'startTime' => 'numeric|nullable',
            'finishTime' => 'numeric|nullable',
            'lang' => 'required|string'
        ];
    }

    public function messages()
    {
        return [
            'type.required' => __('celebration.type_required'),
            'type.numeric' => __('celebration.type_numeric'),
            'type.exists' => __('celebration.type_exists'),
            'FirstName.required' => __('celebration.firstname_required'),
            'FirstName.string' => __('celebration.firstname_string'),
            'LastName.required' => __('celebration.lastname_required'),
            'LastName.string' => __('celebration.lastname_string'),
            'Message.required' => __('celebration.message_required'),
            'Message.string' => __('celebration.message_string'),
            'template.required' => __('celebration.template_required'),
            'template.numeric' => __('celebration.template_numeric'),
            'template.exists' => __('celebration.template_exists'),
            'lang.required' => __('celebration.lang_required'),
            'lang.string' => __('celebration.lang_string')
        ];
    }
}
