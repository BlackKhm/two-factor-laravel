<?php

namespace Modules\TwoFactorAuthCore\Actions;

use Modules\TwoFactorAuthCore\Services\TwoFactorService;

class GenerateNewRecoveryCodeAction
{
    /**
     * The two factor service.
     *
     * @var TwoFactorService
     */
    protected $tfService;

    /**
     * Create a new action instance.
     *
     * @param TwoFactorService $tfService
     * @return void
     */
    public function __construct(TwoFactorService $tfService)
    {
        $this->tfService = $tfService;
    }

    /**
     * Generate new recovery codes for the user.
     *
     * @param  mixed  $user
     * @return void
     */
    public function __invoke($user)
    {
        $user->forceFill([
            'two_factor_recovery_codes' => $this->tfService->encrypt(
                json_encode(
                    $this->tfService->generateRecoveryCodes()
                )
            ),
        ])->save();
    }
}
