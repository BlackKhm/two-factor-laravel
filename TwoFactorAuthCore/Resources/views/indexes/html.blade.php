
<div id="two-factor-app">
    <div class="box box-primary" v-if="defaultLoading">
        <div class="box-header with-border">
            <h3 class="box-title"> @{{ titleLang }} </h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><em class="la la-minus"></em>  </button>
            </div>
        </div>
        
        <!-- /.box-header -->
        <div class="box-body">

            <div v-if="isManagerEnable" class="alert alert-dismissible fade show button-enable-background-message" role="alert">
                <strong>
                    <img class="mr-2 " alt="img" src="{{ asset('images/vtrust/icons/tick-circle.svg') }}">
                </strong> Two-Factor Authentication has been enabled successfully.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div v-if="isManagerDisable" class="alert alert-dismissible fade show button-enable-background-message" role="alert">
                <strong>
                    <img class="mr-2 " alt="img" src="{{ asset('images/vtrust/icons/tick-circle.svg') }}">
                </strong> Two-Factor Authentication has been disabled successfully.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <p v-if="showEnableButton">
                @lang("fsc-2fa::d.Increase your account's security by enabling two-factor authentication (2FA).")
            </p>
        
            <p v-else-if="showDisableButton">
                @lang("fsc-2fa::d.Use a one-time password authenticator on your mobile device to enable two-factor authentication (2FA).")
            </p>

            <p v-if="showConfirmPage">
                Use a one-time password authenticator on your mobile device to enable two-factor authentication (2FA).
            </p>
            
            <p v-if="showConfirmPage">
                @lang("fsc-2fa::d.We highly recommend you use the Google Authenticator App to set up two-factor authentication (2FA). Please open the App and scan the QR Code below.")
            </p>

          
            <p v-if="showDisableButton">
                @lang("fsc-2fa::d.You've already enabled two-factor authentication using one time password authenticators. In order to register a different device, you must first disable two-factor authentication.")
            </p>

        
            <p v-if="showEnableButton ">Status</p>
            <p><a v-if="showEnableButton" class="btn dot btn-sm disabled" role="button" aria-disabled="true">Disable</a></p>
        
            <button class="btn btn-primary" 
                    v-if="showEnableButton" 
                    @click.prevent="twoFactorEnable">
                    @lang('fsc-2fa::d.enable_two_factor_authentication')
                <span class="la la-refresh la-spin loading-spinner" v-if="showEnableButton && isLoading"></span>
            </button>

            <div class="col-md-12 p-0" v-if="showConfirmPage || showDisableButton">
                <div class="col-md-12 p-0 mb-3 img-thumbnail" v-html="twoFactorInfo.qr_code" v-if="showConfirmPage"></div>
                <div class="col-md-12 p-0">
                    <div class="form-group" v-if="showConfirmPage">
                        <label for="pin_code" class="font-weight-600"> @lang('fsc-2fa::d.pin_code') </label>
                        <input  type="number" 
                                class='form-control'
                                placeholder="@lang('fsc-2fa::d.enter_pin_code')" 
                                v-model="verifyCode">
                                <div class="invalid-feedback d-block" v-if="errors.code">code</div>
                    </div>
                    <div class="form-group" v-if="showConfirmPage || showDisableButton">
                        <label for="current_password" class="font-weight-600"> @lang('fsc-2fa::d.current_password') <span class="text-danger">*</span></label>
                        <input  type="password" 
                                class="form-control" 
                                placeholder="@lang('fsc-2fa::d.enter_current_password')" 
                                v-model="userPassword">
                                <div class="invalid-feedback d-block" v-if="errors.password">Your current password is required to register a two-factor authenticator app.</div>
                                
                    </div>
                  
                    <button class="btn btn-primary"
                            v-if="showDisableButton"
                            @click.prevent="twoFactorReGenerateRecoveryCodes">
                            @lang('fsc-2fa::d.Regenerate Recovery Codes')
                        <span class="la la-refresh la-spin loading-spinner" v-if="showDisableButton && isLoading"></span>
                    </button>

                    <button class="btn btn-danger" 
                            @click.prevent="passwordConfirmDialog('twoFactorDisable')"
                            v-if="showDisableButton">
                            @lang('fsc-2fa::d.disable_two_factor_authentication')
                    </button>

                    <button class="btn btn-primary" 
                            @click.prevent="twoFactorConfirm" 
                            v-if="showConfirmPage">
                            @lang('fsc-2fa::d.Register with Two-Factor App')
                        <span class="la la-refresh la-spin loading-spinner" v-if="showConfirmPage && isLoading"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- <span v-else>Loading...</span> --}}
    @include('fsc-2fa::indexes.modal')
</div>
