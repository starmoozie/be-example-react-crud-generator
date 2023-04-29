<?php

namespace App\Http\Requests\Api\Profile;

use App\Http\Requests\Api\BaseRequest;

class UploadRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $max_file = config('base.max_file_upload');

        return [
            'photos' => "required|array",
            'photos.*' => "required|mimes:jpeg,jpg,png|max:{$max_file}"
        ];
    }
}
