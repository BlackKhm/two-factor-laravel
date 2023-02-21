<?php

namespace Modules\TwoFactorAuthCore\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Modules\TwoFactorAuthCore\Services\TwoFactorService;

class PasswordRequireRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password' => 'required'
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $tfService = resolve(TwoFactorService::class);

            if (!Auth::guard('web')->attempt([
                $tfService->username() => request()->user()->{$tfService->username()},
                'password' => request()->password
            ])) {
                $validator->errors()->add('password', 'Incorrect Password');
            }
        });
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return request()->user();
    }
}
