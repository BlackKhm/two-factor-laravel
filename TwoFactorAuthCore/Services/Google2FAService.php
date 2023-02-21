<?php

namespace Modules\TwoFactorAuthCore\Services;

use PragmaRX\Google2FA\Google2FA;
use Illuminate\Contracts\Cache\Repository;
use Modules\TwoFactorAuthCore\Services\TwoFactorService;

class Google2FAService
{
    /**
     * The underlying library providing two factor authentication helper services.
     *
     * @var \PragmaRX\Google2FA\Google2FA
     */
    protected $engine;

    /**
     * The cache repository implementation.
     *
     * @var \Illuminate\Contracts\Cache\Repository|null
     */
    protected $cache;

    /**
     * Create a new two factor authentication provider instance.
     *
     * @param  \PragmaRX\Google2FA\Google2FA  $engine
     * @param  \Illuminate\Contracts\Cache\Repository|null  $cache
     * @return void
     */
    public function __construct(Google2FA $engine, Repository $cache = null)
    {
        $this->engine = $engine;
        $this->cache = $cache;
    }

    /**
     * Generate a new secret key.
     *
     * @return string
     */
    public function generateSecretKey()
    {
        return $this->engine->generateSecretKey();
    }

    /**
     * Get the two factor authentication QR code URL.
     *
     * @param  string  $companyName
     * @param  string  $companyEmail
     * @param  string  $secret
     * @return string
     */
    public function qrCodeUrl($companyName, $companyEmail, $secret)
    {
        return $this->engine->getQRCodeUrl($companyName, $companyEmail, $secret);
    }

    /**
     * Verify the given code.
     *
     * @param  string  $secret
     * @param  string  $code
     * @return bool
     */
    public function verify($secret, $code)
    {
        if (is_int($customWindow = config('fsc-2fa.two-factor-auth-core.window'))) {
            $this->engine->setWindow($customWindow);
        }
        
        $timestamp = $this->engine->verifyKeyNewer(
            $secret,
            $code,
            optional($this->cache)->get($key = 'fsc-2fa.2fa_codes.'.md5($code))
        );

        if ($timestamp !== false) {
            if ($timestamp === true) {
                $timestamp = $this->engine->getTimestamp();
            }

            optional($this->cache)->put($key, $timestamp, ($this->engine->getWindow() ?: 1) * 60);

            return true;
        }

        return false;
    }


    public function replaceRecoveryCode($code)
    {
        $tfService = resolve(TwoFactorService::class);
        $this->forceFill([
            'two_factor_recovery_codes' => $tfService->encrypt(
                str_replace(
                    $code,
                    $tfService->generateRecoveryCode(),
                    $tfService->decrypt($this->two_factor_recovery_codes)
                )
            ),
        ])->save();
    }
}
