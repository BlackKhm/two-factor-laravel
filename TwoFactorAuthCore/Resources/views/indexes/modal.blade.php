<div class="modal fade" id="two-factor-app-modal" tabindex="-1" role="dialog" aria-labelledby="twoFactorAppModal" aria-hidden="true">
    <div class="modal-dialog modal-confirm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header" v-if="modalHeaderTitle">
                <h5 class="modal-title"> @{{ modalHeaderTitle }} </h5>
                <button type="button" 
                        class="close" 
                        data-dismiss="modal" 
                        aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" v-if="modalBody" v-html="modalBody"> </div>
            <div class="modal-footer">
                <button type="button" 
                        class="btn border" 
                        data-dismiss="modal" 
                        v-if="modalCloseTitle">@{{ modalCloseTitle }}</button>
                <button type="button" 
                        class="btn btn-danger" 
                        @click.prevent="passwordConfirmDialogSubmit" 
                        v-if="modalSubmitTitle">@{{ modalSubmitTitle }}</button>
            </div>
        </div>
    </div>
</div>
