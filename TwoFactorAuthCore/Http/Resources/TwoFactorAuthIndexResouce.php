<?php
 
namespace Modules\TwoFactorAuthCore\Http\Resources;
 
use Illuminate\Http\Resources\Json\JsonResource;
 
class TwoFactorAuthIndexResouce extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // when 2fa not yet enable
        return [
            'enable_confirmed' => $this->Is2faEnableConfirmed,
            'enable' => $this->Is2faEnable,
            'qr_code' => $this->TwoFactorQrCodeSvg,
            'qr_code_url' => $this->TwoFactorQrCodeUrl,
            'secret' => $this->DecryptTwoFactorSecret,
            'recovery_codes' => $this->DecryptRecoveryCodesResponse,
            'manager' => $this->Is2faDisabled
        ];
    }
}