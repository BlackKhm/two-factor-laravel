<?php

namespace Modules\TwoFactorAuthCore\Services;

use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Modules\TwoFactorAuthCore\Services\Google2FAService;

class TwoFactorService
{
    /**
     * Get table name
     */
    public function table(): string
    {
        return config('fsc-2fa.migrate.table') ?: 'users';
    }

    /**
     * Get column username
     */
    public function username(): string
    {
        return config('fsc-2fa.migrate.username') ?: 'phone';
    }

    /**
     * Get column personal token
     */
    public function personalToken(): string
    {
        return config('fsc-2fa.migrate.user_token_field') ?: 'personal_token';
    }

    /**
     * Get column personal token expire
     */
    public function personalTokenExpire(): string
    {
        return config('fsc-2fa.migrate.user_token_expire_field')
            ?: $this->personalToken().'_expire';
    }


    /**
     * decrypt algo
     *
     * @param string $value
     * @param bool $unserialize
     * 
     * @return false|string
     */
    public function decrypt($value, $unserialize = true)
    {

        return $value ? decrypt($value, $unserialize) : false;
    }
    
    /**
     * encrypt algo
     *
     * @param mixed $value
     * @param bool $unserialize
     * 
     * @return false|string
     */
    public function encrypt($value, $serialize = true)
    {
        return $value ? encrypt($value, $serialize) : false;
    }

    /**
     * Generate a new recovery code
     */
    public function generateRecoveryCode(): ?string
    {
        return Str::random(10).'-'.Str::random(10);
    }

    /**
     * Generate a new recovery codes
     */
    public function generateRecoveryCodes(): array
    {
        return collect()->times(8, function () {
            return $this->generateRecoveryCode();
        })->all();
    }

    /**
     * Check verify pin code
     * $code : get input code 
     * $userLogin: user information
     */
    public function verify($code, $userLogin)
    {
        if(! resolve(Google2FAService::class)->verify(decrypt($userLogin->two_factor_secret), $code)){
            if (! in_array($code, $userLogin->DecryptRecoveryCodes)) {
                return false;
            } else {
                $userLogin->replaceRecoveryCode($code);
            }
        }

        return true;
    }

    public function shouldGoToVerifyPage()
    {
        $user = request()->user();
        
        if($user->two_factor_confirmed_at){
            request()->session()->put(config('fsc-2fa.two-factor-auth-core.verify-page-session'), true);

            Auth::logout();

            $encrypt = $this->encrypt(
                $user->id.','.request()->password
            );

            return redirect()->to(config('fsc-2fa.two-factor-auth-core.verify-page').'?q='.$encrypt);
        }

        return false;
    }
}
