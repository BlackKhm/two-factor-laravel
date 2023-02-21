<?php

namespace Modules\TwoFactorAuthCore\Traits\Model;

use BaconQrCode\Writer;
use BaconQrCode\Renderer\Color\Rgb;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\Fill;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use Modules\TwoFactorAuthCore\Services\Google2FAService;
use Modules\TwoFactorAuthCore\Services\TwoFactorService;

trait UserTwoFactorAuthTrait
{
    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

     /**
     * Replace the given recovery code with a new one in the user's stored codes.
     *
     * @param  string  $code
     * @return void
     */
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
            )
        ])->save();
    }
    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    public function getIs2faEnableAttribute(): bool
    {
        return $this->two_factor_secret
            && $this->two_factor_recovery_codes;
    }

    public function getIs2faEnableConfirmedAttribute(): bool
    {
        return $this->Is2faEnable
            && $this->two_factor_confirmed_at;
    }

    public function getIs2faDisabledAttribute(): bool
    {
        return ! $this->Is2faEnableConfirmed;
    }
    
    public function getDecryptRecoveryCodesResponseAttribute(): array
    {
        if ((! $this->Is2faEnable || $this->Is2faEnableConfirmed) && ! request()->forceShowRecoveryCode) {
            return [];
        }

        return $this->DecryptRecoveryCodes;
    }

    public function getDecryptRecoveryCodesAttribute(): array
    {
        return json_decode(
            resolve(TwoFactorService::class)->decrypt($this->two_factor_recovery_codes),
            true
        );
    }

    public function getDecryptTwoFactorSecretAttribute(): string
    {
        if (! $this->Is2faEnable || $this->Is2faEnableConfirmed) {
            return '';
        }
        
        return resolve(TwoFactorService::class)->decrypt($this->two_factor_secret);
    }
    
    public function getTwoFactorQrCodeSvgAttribute(): string
    {
        if ($qrCodeUrl = $this->TwoFactorQrCodeUrl) {
            $svg = (new Writer(
                new ImageRenderer(
                    new RendererStyle(
                        192,
                        0,
                        null,
                        null,
                        Fill::uniformColor(
                            new Rgb(255, 255, 255),
                            new Rgb(45, 55, 72)
                        )
                    ),
                    new SvgImageBackEnd
                )
            ))->writeString($qrCodeUrl);
    
            return trim(substr($svg, strpos($svg, "\n") + 1));
        }
        
        return '';
    }

    /**
     * Get the two factor authentication QR code URL.
     */
    public function getTwoFactorQrCodeUrlAttribute(): string
    {
        if (! $this->Is2faEnable || $this->Is2faEnableConfirmed) {
            return '';
        }

        return resolve(Google2FAService::class)->qrCodeUrl(
            env('TWO_FACTOR_AUTH_CORE_NAME', config('app.name')),
            $this->{resolve(TwoFactorService::class)->username()},
            $this->DecryptTwoFactorSecret
        );
    }
}
