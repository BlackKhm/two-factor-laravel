<?php

namespace Modules\TwoFactorAuthCore\Actions;

use Illuminate\Validation\ValidationException;
use Modules\TwoFactorAuthCore\Services\Google2FAService;
use Modules\TwoFactorAuthCore\Services\TwoFactorService;

class ConfirmTwoFactorAuthAction
{
    /**
     * The two factor authentication provider.
     *
     * @var Google2FAService
     */
    protected $provider;

    /**
     * The two factor service.
     *
     * @var TwoFactorService
     */
    protected $tfService;

    /**
     * Create a new action instance.
     *
     * @param Google2FAService $provider
     * @return void
     */
    public function __construct(Google2FAService $provider, TwoFactorService $tfService)
    {
        $this->provider = $provider;
        $this->tfService = $tfService;
    }

    /**
     * Confirm the two factor authentication configuration for the user.
     *
     * @param  mixed  $user
     * @param  string  $code
     * @return void
     */
    public function __invoke($user, $code)
    {
        if (! $user->two_factor_secret
            || ! $code
            || ! $this->provider->verify(
                $this->tfService->decrypt($user->two_factor_secret),
                $code
            )
        ) {
            throw ValidationException::withMessages([
                'code' => [__('fsc-2fa::d.The provided two factor authentication code was invalid.')],
            ])->errorBag('confirmTwoFactorAuthentication');
        }

        $user->forceFill([
            'two_factor_confirmed_at' => now(),
        ])->save();
    }
}
