<template>
    <b-modal v-model="showModal" title="Create Disbursement" header-class="p-3 bg-light" style="--vz-modal-width: 590px;" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>    
        <b-form class="customform mb-2">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Amount: <span v-if="form.errors" v-text="form.errors.amount" class="haveerror"></span></label>
                        <input type="text" class="form-control" v-model="disbursement.amount">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group mb-2">
                        <label>Expense: <span v-if="form.errors" v-text="form.errors.expense_id" class="haveerror"></span></label>
                        <Multiselect 
                        v-model="disbursement.expense" 
                        :options="expenses"
                        :allow-empty="false"
                        :show-labels="false"
                        label="name" track-by="id"
                        placeholder="Select Expense"/>
                    </div>
                </div>
                 <div class="col-md-12">
                    <div class="form-group">
                        <label>Remarks: <span v-if="form.errors" v-text="form.errors.remarks" class="haveerror"></span></label>
                        <input type="text" class="form-control" v-model="disbursement.remarks">
                    </div>
                </div>
            </div>
        </b-form>
        <template v-slot:footer>
            <b-button @click="showModal = false" variant="light" block>Cancel</b-button>
            <b-button @click="create('ok')" variant="primary" block>Save</b-button>
        </template>
    </b-modal>
</template>

<script>
import Multiselect from '@suadelabs/vue3-multiselect';
export default {
    components : { Multiselect },
    props: ['expenses'],
    data() {
        return {
            currentUrl: window.location.origin,
            form: {},
            disbursement: {
                amount: '',
                expense: '',
                remarks: ''
            },
            showModal: false
        }
    },
    methods: {
        set() {
            this.showModal = true;
        },
        create(){
            this.form = this.$inertia.form({
                amount: this.disbursement.amount,
                expense_id: (this.disbursement.expense) ? this.disbursement.expense.id : '',
                remarks: (this.disbursement.remarks) ? this.disbursement.remarks : 'n/a',
                type: 'disbursement'
            })

            this.form.post('/accounting',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.hide();
                    this.$emit('info',true);
                },
            });
        },
        hide(){
            this.disbursement = {};
            this.showModal = false;
        }
    }
}
</script>
