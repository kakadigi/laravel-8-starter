<?php

namespace Modules\Membership\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMember extends FormRequest
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
            'gender' => 'sometimes|in:male,female',
            'identity.*.type' => 'sometimes|required|in:ktp,sim,npwp,surat_tugas',
            'password' => 'sometimes|min:6|string|confirmed'
        ];
    }
}
