<?php

namespace Modules\TwoFactorAuthCore\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Modules\TwoFactorAuthCore\Services\Google2FAService;
use Modules\TwoFactorAuthCore\Services\TwoFactorService;
use Modules\TwoFactorAuthCore\Services\TwoFactorMigrateService;
use Modules\TwoFactorAuthCore\Actions\EnableTwoFactorAuthAction;
use Modules\TwoFactorAuthCore\Actions\ConfirmTwoFactorAuthAction;
use Modules\TwoFactorAuthCore\Actions\DisableTwoFactorAuthAction;
use Modules\TwoFactorAuthCore\Http\Requests\PasswordRequireRequest;
use Modules\TwoFactorAuthCore\Actions\GenerateNewRecoveryCodeAction;
use Modules\TwoFactorAuthCore\Http\Resources\TwoFactorAuthIndexResouce;

class TwoFactorAuthSessionController extends Controller
{
    /**
     * Get User Information
     *
     * @param Request $request
     */
    public function index(Request $request)
    {
       if($request->session()->get(config('fsc-2fa.two-factor-auth-core.verify-page-session'))){
            return view('fsc-2fa::auth.pin_code');
       }

        return abort(404);
    }

    public function store(Request $request)
    {
        $decrypt = explode(',', resolve(TwoFactorService::class)->decrypt($request->q));

        $userId = $decrypt[0] ?? false;
        $password = $decrypt[1] ?? '';

        $userLogin = config('fsc-2fa.user_model')::where('id' , $userId)->first();

        if($userLogin && resolve(TwoFactorService::class)->verify($request->code, $userLogin)){
            Auth::loginUsingId($userId);

            if (config('fsc-2fa.two-factor-auth-core.enable-logout-other-device')) {
                Auth::logoutOtherDevices($password);
            }

            return redirect()->to(config('fsc-2fa.two-factor-auth-core.verify-login-redirect-to'));
        }

        return redirect()
            ->to(config('fsc-2fa.two-factor-auth-core.verify-page').'?q='.request()->q)
            ->withInput()
            ->with('message','pin code invalide');
    }
}
