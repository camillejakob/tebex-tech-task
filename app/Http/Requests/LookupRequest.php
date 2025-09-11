<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LookupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [
            'username' => ['required_without:id', 'nullable', 'string'],
            'id' => ['required_without:username', 'required_if:type,steam', 'nullable', 'string'],
            'type' => ['required', 'string', 'in:xbl,steam,minecraft'],
        ];

        if (request()->query('type') == 'steam') {
            $rules = array_merge($rules, [
                'username' => ['prohibited_if:type,steam', 'nullable', 'string'],
            ]);
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'username.prohibited_if' => 'Steam only supports IDs.',
            'id.required_if' => 'Steam id is required.',
        ];
    }
}
