<template>
    <b-modal v-model="showModal" title="Lock Enrollment Information" header-class="p-3 bg-light" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>    
        <b-form class="customform">
            <div class="row">
                <div class="col-md-12 mt-2 mb-n3">
                    <div class="alert alert-danger alert-dismissible alert-label-icon rounded-label" role="alert"><i class="ri-error-warning-line label-icon"></i>You won't be able to update the grades anymore! </div>
                </div>
            </div>
        </b-form>
        <template v-slot:footer>
            <b-button @click="showModal = false" variant="light" block>Cancel</b-button>
            <b-button @click="save('ok')" variant="primary"  block>Save</b-button>
        </template>
    </b-modal>
</template>

<script>
export default {
    props: ['lists'],
    data() {
        return {
            currentUrl: window.location.origin,
            showModal: false,
            id: ''
        }
    },
    methods: {
        set(id){
            this.id = id;
            this.showModal = true;
        },
        save(){
            this.form = this.$inertia.form({
                id: this.id,
                is_locked: true,
                editable: true,
                lists: this.lists
            })

            this.form.post('/grade/store',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.showModal = false;
                }
            });
        }
    }
}
</script>
