<?php

namespace Modules\TwoFactorAuthCore\Actions;

use Modules\TwoFactorAuthCore\Services\TwoFactorService;

class IssuePersonalTokenAction
{
    /**
     * Two Factor Service
     *
     * @var TwoFactorService
     */
    protected $tfService;

    /**
     * __construct
     *
     * @param TwoFactorService $tfService
     */
    public function __construct(TwoFactorService $tfService)
    {
        $this->tfService = $tfService;
    }
   
    /**
     * Disable two factor authentication for the user.
     *
     * @param  mixed  $user
     * @return void
     */
    public function __invoke($user)
    {
        $token = $user->createToken('PersonalAccessTokenForWeb_'.env('APP_NAME'));

        $user->forceFill([
            $this->tfService->personalToken() => $token->accessToken,
            $this->tfService->personalTokenExpire() => optional($token->token)->expires_at
        ])->save();

        return $user;
    }
}
