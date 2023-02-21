# NOTE

- New Version of this Module is Full Replacing
    - If u has make any modify inside TwoFactorAuthCore, you are ready to maintenance this Module yourself

## REQUIREMENT

- install google2fa: `composer require pragmarx/google2fa`

    - if u have `laravel/fortify` it already use install this package

- install bacon-qr-code: `composer require bacon/bacon-qr-code`

    - if u have `simplesoftwareio/simple-qrcode` it already use install this package

## INTRODUCTION

- [x] Enable/Disable/Comfirm Two Factor API

- [x] Generate Personal Token for API by Middleware

- [x] Reset two factor auth with recovery code

### INSTALLATION

- Copy `TwoFactorAuthCore` folder into `Modules` folder

- Enable this module `php artisan module:enable TwoFactorAuthCore`

- Disable this module `php artisan module:disable TwoFactorAuthCore`

- Publish other avialable resource: `php artisan vendor:publish --provider="Modules\TwoFactorAuthCore\Providers\TwoFactorAuthCoreServiceProvider"`

- Update config base on your reference project before using this module

- Run `php artisan migrate`

- When copy folder pass on project don't have `PersonalAccessTokenForWeb::class` you need to add on kernel (web)

- Define env `TWO_FACTOR_AUTH_CORE_NAME=<Name>` or we get from env `APP_NAME`

### METHODS

- Validate 2fa code `resolve(TwoFactorService::class)->verify($code, $userLogin)`

### VIEWS

- html & script to your backend page (enable, disable 2fa)

    - `@include('fsc-2fa::indexes.html')` : this check form enable disable and recoveryCode 
    
    - `@include('fsc-2fa::indexes.script')` : this include file on after script it work anywhere 

- pin_code to your verify page (after login page) 
    - `@include('fsc-2fa::auth.pin_code')` : this when enable success include on when login success, it will rederec confirm pin code

### MODEL

- `use UserTwoFactorAuthTrait;` on User Model

### LOGIN CONTROLLER

- Check this line after pass login/$this->attempt()

```
if ($shouldGoToVerifyPage = resolve(TwoFactorService::class)->shouldGoToVerifyPage()) {
    return $shouldGoToVerifyPage;
}
```

### ROUTE API

- route url api (enable disable 2fa)

    - Get two-factor information, GET: `api/two-factor`

    - Enable two-factor, POST: `api/two-factor`

    - Disable two-factor, DELETE: `api/two-factor`

    - Comfirn two-factor after Enable, POST: `api/two-factor/confirm`

    - Regenerate two-factor recovery code, POST: `api/two-factor/recovery-code`

## CHANGE LOGS

### v1.1.0

- First release with major breaking change