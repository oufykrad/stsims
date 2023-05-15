<template>
    <b-modal v-model="showModal" :title="(!editable) ? 'Create Sub Allotment' : 'Edit Sub Allotment'" header-class="p-3 bg-light" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>    
        <b-form class="customform mb-2">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group mb-2">
                        <label>Expense: <span v-if="form.errors" v-text="form.errors.expense_id" class="haveerror"></span></label>
                        <Multiselect 
                        v-model="allotment.expense" 
                        :options="expenses"
                        :allow-empty="false"
                        :show-labels="false"
                        label="name" track-by="id"
                        placeholder="Select Expense"/>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Amount : <span v-if="form.errors" v-text="form.errors.check_no" class="haveerror"></span></label>
                        <input type="text" class="form-control" v-model="allotment.amount">
                    </div>
                </div>
                 <div class="col-md-12">
                    <div class="form-group">
                        <label>Remarks: <span v-if="form.errors" v-text="form.errors.remarks" class="haveerror"></span></label>
                        <input type="text" class="form-control" v-model="allotment.remarks">
                    </div>
                </div>
            </div>
        </b-form>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="create('ok')" variant="primary" block>
                <span v-if="!editable">Save</span>
                <span v-else>Update</span>
            </b-button>
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
            allotment: {
                id: '',
                amount: '',
                remarks: '',
                expense: ''
            },
            showModal: false,
            editable: false
        }
    },
    methods: {
        set(id) {
            this.allotment.id = id;
            this.showModal = true;
        },
        edit(data) {
            this.allotment = data;
            this.showModal = true;
            this.editable = true;
        },
        create(){
            this.form = this.$inertia.form({
                allotment_id: this.allotment.id,
                amount: this.allotment.amount,
                expense_id: (this.allotment.expense) ? this.allotment.expense.id : '',
                remarks: (this.allotment.remarks) ? this.allotment.remarks : 'n/a',
                type: 'suballotment'
            });

            this.form.post('/accounting',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.hide();
                    this.$emit('info',true);
                },
            });
        },
        hide(){
            this.allotment = {};
            this.showModal = false;
            this.editable = false;
        }
    }
}
</script>
