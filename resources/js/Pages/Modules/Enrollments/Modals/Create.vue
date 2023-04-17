<template>
    <b-modal v-model="showModal" title="Confirm Enrollment" header-class="p-3 bg-light" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>    
        <div class="modal-body text-center">
            <div class="mt-4" v-if="Object.keys(errors).length === 0">
                <p class="text-muted mb-4">Are you sure you want to add scholar's certificate of registration?</p>
            </div>
             <div class="mt-0" v-else>
                <p class="text-muted mb-4">Please check the following fields.</p>
            </div>
            <div class="live-preview">
                <ul class="list-group">
                    <li class="list-group-item" v-for="(error,index) in errors" v-bind:key="index"><i class="mdi mdi-check-bold align-middle lh-1 me-2"></i> 
                        {{ error }} 
                    </li>
                </ul>
            </div>
        </div>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="create('ok')" variant="primary" :disabled="form.processing" block>Save</b-button>
        </template>
    </b-modal>
</template>
<script>
export default {
    props: ['user'],
    data() {
        return {
            currentUrl: window.location.origin,
            errors: [],
            value: '',
            enrollments: [],
            class_type: '',
            showModal: false,
            form: {}
        }
    },

    created() {
        this.enrollments = this.user.enrollments;
        this.class_type = this.user.education.school.class;
    },

    methods: {
        set(value) {
            this.value = value;
            this.showModal = true;
        },

        create() {
            this.$inertia.post('/enrollments', this.value, {
                preserveScroll: true,
                forceFormData: true,
                onSuccess: (response) => {
                    this.showModal = false;
                },
                onError: () => {
                    this.errors = this.$page.props.errors;
                }
            });
        },

        hide() {
            this.showModal = false;
            this.errors = [];
        }
    }
}
</script>
