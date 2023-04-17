<template>
    <b-modal v-model="showModal" hide-footer class="v-modal-custom" modal-class="zoomIn" body-class="p-0" centered hide-header-close>
        <div class="text-center px-5 pt-4">
            <div class="text-end">
                <button type="button" class="btn-close text-end" @click="showModal = false" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="mt-2">
                <h4 class="mb-3 mt-4">Your Transaction is Successfull !</h4>
                <p class="text-muted fs-15 mb-4">Successful transaction is the status of
                    operation whose result is the payment of the amount paid by the customer
                    in favor of the merchant.</p>
                <div class="hstack gap-2 justify-content-center">
                    <b-button type="button" variant="primary" :disabled="form.processing" @click="download()">New transaction</b-button>
                </div>
            </div>
        </div>
        <div class="modal-footer bg-light p-1 mt-5 justify-content-center">
           <b-card no-body class="bg-light overflow-hidden shadow-none w-100">
                <b-card-body>
                  <div class="d-flex">
                    <div class="flex-grow-1">
                      <h6 class="mb-0">
                        <b class="text-secondary">{{ (form.progress) ? form.progress.percentage : ''}}</b> Downloading in progress...
                      </h6>
                    </div>
                    <div class="flex-shrink-0">
                      <h6 class="mb-0">1 min left</h6>
                    </div>
                  </div>
                </b-card-body>

                <b-progress class="bg-soft-secondary rounded-0">
                  <b-progress-bar :value="(form.progress) ? form.progress.percentage : ''" max="100" variant="secondary" />
                </b-progress>
              </b-card>
        </div>
    </b-modal>
</template>
<script>
export default {
    data(){
        return {
            currentUrl: window.location.origin,
            form: {},
            showModal: false,

        }
    },
    methods : {
        show(){
            this.showModal = true;
        },
        download() {
            this.form = this.$inertia.form();
            this.form.get(this.currentUrl + '/sync/addresses/download/all',{
                preserveScroll: true,
                forceFormData: true,
                onSuccess: (response) => {
                    this.hide();
                }
            });

            // axios.get(this.currentUrl + '/sync/addresses/download/all')
            // .then(response => {})
            // .catch(err => console.log(err));
        },

        hide(){

        }
    }
}
</script>