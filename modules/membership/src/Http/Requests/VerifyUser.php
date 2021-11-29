<?php

namespace Modules\Membership\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Modules\Membership\Member;

class VerifyUser extends FormRequest
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
        Validator::extend('validated_verify_url', function ($attribute, $value) {
            $uuid = Crypt::decryptString($value);
            $verified = Member::where('uuid', $uuid)->count();
            return $verified;
        });
        return [
            'verify' => 'required|validated_verify_url'
        ];
    }

    public function messages()
    {
        return [
            'validated_verify_url' => 'Mohon maaf, URL tidak valid untuk diverifikasi.'
        ];
    }
}
