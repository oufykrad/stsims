<template>
    <b-modal v-model="showModal" title="View Reimbursement" style="--vz-modal-width: 600px;" header-class="p-3 bg-light" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>    
        <b-form class="customform mb-2 mt-2">
            <div class="row">
               
            </div>
        </b-form>
        <template v-slot:footer>
            <b-button @click="showModal = false" variant="light" block>Cancel</b-button>
            <b-button v-if="disbursement.is_approved == 0" @click="create('ok')" variant="primary"  block>Approve</b-button>
        </template>
    </b-modal>
</template>
<script>
export default {
    data() {
        return {
            currentUrl: window.location.origin,
            showModal: false,
            disbursement: '',
            form: {}
        }
    },
    methods: {
        set(data) {
            this.disbursement = data;
            this.showModal = true;
        },
        create(){
            this.form = this.$inertia.form({
                id: this.disbursement.id,
                is_approved: 1
            })

            this.form.put('/reimbursements/update',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.hide();
                }
            });
        },
        hide(){
            this.showModal = false;
            this.disbursement = '';
        }
    }
}
</script>
