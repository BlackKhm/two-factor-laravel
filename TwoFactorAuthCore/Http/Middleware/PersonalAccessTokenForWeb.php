<?php

namespace Modules\TwoFactorAuthCore\Http\Middleware;

use Closure;
use Modules\TwoFactorAuthCore\Services\TwoFactorService;
use Modules\TwoFactorAuthCore\Actions\IssuePersonalTokenAction;

class PersonalAccessTokenForWeb
{
    /**
     * Two Factor Service
     *
     * @var TwoFactorService
     */
    protected $tfService;

    /**
     * Two Factor Service
     *
     * @var IssuePersonalTokenAction
     */
    protected $issuePersonToken;

    /**
     * __construct
     *
     * @param TwoFactorService $tfService
     */
    public function __construct(TwoFactorService $tfService, IssuePersonalTokenAction $issuePersonToken)
    {
        $this->tfService = $tfService;
        $this->issuePersonToken = $issuePersonToken;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($user = request()->user()) {
            // when token or expire token is null
            // when token expire is less than now
            // then generate new token and expire
            if (! $user->{$this->tfService->personalToken()}
                || ! $user->{$this->tfService->personalTokenExpire()}
                || $user->{$this->tfService->personalTokenExpire()} <= now()
            ) {
                $user = call_user_func($this->issuePersonToken, $user);
            }
    
            view()->share([
                'accessToken' => 'Bearer '.$user->{$this->tfService->personalToken()}
            ]);
        }
        
        return $next($request);
    }
}
