<?php

namespace Modules\TwoFactorAuthCore\Actions;

use Modules\TwoFactorAuthCore\Services\Google2FAService;
use Modules\TwoFactorAuthCore\Services\TwoFactorService;

class EnableTwoFactorAuthAction
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
     * @param TwoFactorService $tfService
     * @return void
     */
    public function __construct(Google2FAService $provider, TwoFactorService $tfService)
    {
        $this->provider = $provider;
        $this->tfService = $tfService;
    }

    /**
     * Enable two factor authentication for the user.
     *
     * @param  mixed  $user
     * @return void
     */
    public function __invoke($user)
    {
        $user->forceFill([
            'two_factor_secret' => $this->tfService->encrypt(
                $this->provider->generateSecretKey()
            ),
            'two_factor_recovery_codes' => $this->tfService->encrypt(
                json_encode(
                    $this->tfService->generateRecoveryCodes()
                )
            ),
        ])->save();
    }
}
