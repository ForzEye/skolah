<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UploadFaviconRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'favicon' => ['required', 'file', 'mimes:jpg,jpeg,png,ico,webp,svg', 'max:1024'],
        ];
    }

    public function messages(): array
    {
        return [
            'favicon.required' => 'Pilih file favicon terlebih dahulu.',
            'favicon.file'     => 'File favicon tidak valid.',
            'favicon.mimes'    => 'Format favicon harus JPG, PNG, ICO, SVG, atau WebP.',
            'favicon.max'      => 'Ukuran favicon maksimal 1 MB.',
        ];
    }
}
