<?php

namespace Modules\TwoFactorAuthCore\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\TwoFactorAuthCore\Actions\EnableTwoFactorAuthAction;
use Modules\TwoFactorAuthCore\Actions\ConfirmTwoFactorAuthAction;
use Modules\TwoFactorAuthCore\Actions\DisableTwoFactorAuthAction;
use Modules\TwoFactorAuthCore\Http\Requests\PasswordRequireRequest;
use Modules\TwoFactorAuthCore\Actions\GenerateNewRecoveryCodeAction;
use Modules\TwoFactorAuthCore\Http\Resources\TwoFactorAuthIndexResouce;

class TwoFactorAuthController extends Controller
{
    /**
     * Indicate Login User
     */
    protected $user;

    public function __construct()
    {

        $this->middleware(function ($request, $next) {

            $this->user = $request->user();

            return $next($request);

        });
        
    }
    /**
     * Get User Information
     *
     * @param Request $request
     */
    public function index(Request $request): TwoFactorAuthIndexResouce
    {
        return new TwoFactorAuthIndexResouce($this->user);
    }

    /**
     * Enable two factor authentication for the user.
     *
     * @param Request $request
     * @param EnableTwoFactorAuthAction $enable
     */
    public function store(Request $request, EnableTwoFactorAuthAction $enable): TwoFactorAuthIndexResouce
    {
        $enable($this->user);

        return new TwoFactorAuthIndexResouce($this->user);
    }

    /**
     * Disable two factor authentication for the user.
     *
     * @param Request $request
     * @param DisableTwoFactorAuthentication $disable
     */
    public function destroy(
        PasswordRequireRequest $request,
        DisableTwoFactorAuthAction $disable
    ): TwoFactorAuthIndexResouce
    {
        $disable($this->user);

        return new TwoFactorAuthIndexResouce($this->user);
    }

    /**
     * Generate a fresh set of two factor authentication recovery codes.
     *
     * @param Request $request
     * @param GenerateNewRecoveryCodeAction $generate
     */
    public function storeRecoveryCode(
        PasswordRequireRequest $request,
        GenerateNewRecoveryCodeAction $generate
    ): TwoFactorAuthIndexResouce
    {
        $generate($this->user);
        $this->forceShowRecoveryCode();

        return new TwoFactorAuthIndexResouce($this->user);
    }

    /**
     * Cormfirm Two Factor Authentication
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function storeConfirm(
        PasswordRequireRequest $request,
        ConfirmTwoFactorAuthAction $confirm
    ): TwoFactorAuthIndexResouce
    {
        $confirm($this->user, $request->input('code'));
        $this->forceShowRecoveryCode();

        return new TwoFactorAuthIndexResouce($this->user);
    }
    /**
     * Force recovery to be show in response
     */
    protected function forceShowRecoveryCode(): void
    {
        request()->merge([
            'forceShowRecoveryCode' => true
        ]);
    }
}
