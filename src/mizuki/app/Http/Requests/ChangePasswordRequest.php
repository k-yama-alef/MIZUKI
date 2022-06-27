<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// 追加開始
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
// 追加終了
class ChangePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // return false;
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
            // 追加開始
            'current_password' => ['required', 'string', 'min:5'],
            'password' => ['required', 'string', 'min:5', 'confirmed']
            // 追加終了
        ];
    }
    // 追加開始
    public function withValidator(Validator $validator) {
        $validator->after(function ($validator) {
            $auth = Auth::user();

            //現在のパスワードと新しいパスワードが合わなければエラー
            if (!(Hash::check($this->input('current_password'), $auth->password))) {
                $validator->errors()->add('current_password', __('The current password is incorrect.'));
            }
        });
    }
    // 追加終了
}
