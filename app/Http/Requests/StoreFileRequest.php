<?php

namespace App\Http\Requests;

use App\Models\File;
use Illuminate\Foundation\Http\FormRequest;

class StoreFileRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'content' => ['required_if:type,file', 'string', 'nullable'],
            'type' => ['required', 'in:file,folder'],
            'parent_id' => ['nullable', 'exists:files,id']
        ];
    }
}
