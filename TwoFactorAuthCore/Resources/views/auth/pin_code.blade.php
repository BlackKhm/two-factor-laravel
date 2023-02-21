<div id="two-factor-app">
    <form class="col-md-12 p-t-10 login-form" role="form" method="POST" action="{{ url(config('fsc-2fa.two-factor-auth-core.verify-page')).'?q='.request()->q }}">
        @csrf
        <div class="form-group required">
            <div>
                <input
                    name="code"
                    type="text"
                    class="form-control verify_pin_code_style"
                    placeholder="@lang('fsc-2fa::d.enter_pin_code')"
                >
                @if (Session::has('message'))
                    <span class="text-warning">Pin code invalid</span>
                @endif
            </div>
        </div>
        <div class="form-group">
            <p class="class-font-size-12 pt-2">
                Please enter the code from the two-factor app on your mobile device.
            </p>
        </div>
        <div class="form-group">
            <div class="mt-4 btn-submit">
                <button  type="submit" class="btn btn-block btn-dark"> Verify Code </button>
            </div>
        </div>
    </form>
</div>