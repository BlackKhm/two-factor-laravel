asdasdasdasd
<script>
const twoFactorAppConst = {
    RECOVER_CODE: 'RECOVER_CODE',
};

var twoFactorApp = new Vue({
    el: '#two-factor-app',
    data () {
        return {
            errors: {},
            isLoading: false,
            twoFactorInfo: {},
            userPassword: '',
            verifyCode: '',
            dialogAction: '',
            is_valid_code: false,
            is_valid_password: false,
            is_valid_password_disable: false,
            isManagerEnable: false,
            isManagerDisable: false,
            defaultLoading: false,
        };
    },
    computed: {
        titleLang () {
            if (this.showEnableButton) {
                return '@lang('fsc-2fa::d.two_factor_authentication')'
            }

            if (this.showConfirmPage) {
                return '@lang('fsc-2fa::d.Register Two-Factor Authenticator')'
            }

            if (this.showDisableButton) {
                return '@lang('fsc-2fa::d.two_factor_authentication')'
            }

            return ''
        },
        modalHeaderTitle () {
            return '@lang('fsc-2fa::d.are_you_sure')'
        },
        modalBody () {
            if (this.dialogAction != twoFactorAppConst.RECOVER_CODE) {
                return '@lang('fsc-2fa::d.this_will_invalidate_your_registered_applications')'
            }
            return `<code>${this.recoveryCodeToHtml()}</code>`
        },
        modalCloseTitle () {
            return '@lang('fsc-2fa::d.cancel')'
        },
        modalSubmitTitle () {
            if (this.dialogAction == twoFactorAppConst.RECOVER_CODE) {
                return ''
            }

            return '@lang('fsc-2fa::d.disable')';
        },
        showEnableButton () {
            return ! this.twoFactorInfo.enable;
            // return ! this.isLoading
            //     && ! this.twoFactorInfo.enable
        },
        showDisableButton () {
            return ! this.isLoading
                && this.twoFactorInfo.enable_confirmed
        },
        showConfirmPageManager () {
            return this.twoFactorInfo.enable
                &&  this.twoFactorInfo.enable_confirmed
          
        }, 
        showConfirmPage () {
            return this.twoFactorInfo.enable
                && ! this.twoFactorInfo.enable_confirmed
        }
    },
    methods: {
        recoveryCodeToHtml () {
            let code = ''
            this.twoFactorInfo.recovery_codes.forEach(function (v) {
                code += `<p>${v}</p>`
            })
            return code
        },
        alert (text, type = 'success') {
            new Noty({
                type: type,
                text: text
            }).show();
        },
        modalToggle() {
            $('#two-factor-app-modal').modal('toggle')
        },
        passwordConfirmDialog (action) {
            this.dialogAction = action

            this.modalToggle()
        },
        passwordConfirmDialogSubmit () {
            this.modalToggle()

            if (this.dialogAction != twoFactorAppConst.RECOVER_CODE) {
                this[this.dialogAction]()

                this.dialogAction = ''
            }
        },
        twoFactorReGenerateRecoveryCodes () {
            if (this.isLoading) {
                return ;
            }

            this.isLoading = true

            axios.post(`{{ route('two-factor.recovery-code.store') }}`, {
                password: this.userPassword
            }, {
                headers: {
                    Authorization: `{{ $accessToken }}`
                }
            })
                .then(res => {
                    this.twoFactorInfo = res.data.data
                    this.userPassword = ''
                    this.passwordConfirmDialog(twoFactorAppConst.RECOVER_CODE)
                    this.alert('Success Comfirm')
                })
                .catch(e => {
                    this.alert('Fail Confirm', 'danger')
                })
                
            this.isLoading = false
        },
        twoFactorConfirm () {
            if (this.isLoading) {
                return ;
            }

            this.isLoading = true

            axios.post(`{{ route('two-factor.confirm') }}`, {
                code: this.verifyCode,
                password: this.userPassword
            }, {
                headers: {
                    Authorization: `{{ $accessToken }}`
                }
            })
                .then(res => {
                    this.twoFactorInfo = res.data.data
                    this.verifyCode = this.userPassword = ''
                    this.isManagerEnable = true
                })
                .catch(e => {
                    this.is_valid_code = true;
                    this.is_valid_password = true;
                    if (e.response.status === 422) {
                        this.errors = e.response.data.errors;
                    }
                })
                
            this.isLoading = false
        },
        twoFactorEnable () {
            if (this.isLoading) {
                return ;
            }

            this.isLoading = true

            axios.post(`{{ route('two-factor.store') }}`, {
                // password: this.userPassword
            }, {
                headers: {
                    Authorization: `{{ $accessToken }}`
                }
            })
                .then(res => {
                    this.twoFactorInfo = res.data.data
                    this.isManagerDisable = false
                    // this.userPassword = ''
                })
                .catch(e => {
                    // this.isLoading = false
                    // this.alert('Fail Enable', 'danger')
                })

            this.isLoading = false
        },
        
        twoFactorDisable () {
            if (this.isLoading) {
                return ;
            }

            this.isLoading = true

            axios.delete(`{{ route('two-factor.destroy') }}`, {
                headers: {
                    Authorization: `{{ $accessToken }}`
                    
                },
                data: {
                    password: this.userPassword
                }
            })
                .then(res => {
                    this.twoFactorInfo = res.data.data
                    this.userPassword = ''
                    this.isManagerEnable = false
                    this.isManagerDisable = true
                })
                .catch(e => {
                    this.is_valid_password_disable = true;
                    if (e.response.status === 422) {
                        this.errors = e.response.data.errors;
                    }
                })

            this.isLoading = false
        },
        loadIndex () {
            this.isLoading = true

            axios.get(`{{ route('two-factor.index') }}`, {
                headers: {
                    Authorization: `{{ $accessToken }}`
                }
            })
                .then(res => {
                    this.twoFactorInfo = res.data.data
                })
                .catch(e => {
                    // this.isLoading = false
                })
                .finally(() => {
                    this.isLoading = false;
                    this.defaultLoading = true;
                });

        }
    },
    mounted () {
        this.loadIndex()
    }
})
</script>
