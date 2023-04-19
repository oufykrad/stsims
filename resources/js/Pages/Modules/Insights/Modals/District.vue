<template>
    <b-modal v-model="showModal" title="District" size="xl" header-class="p-3 bg-light" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>    
        <b-form class="customform mb-2">
            <div class="row">
                <div class="col-md-12">
                    
                </div>
            </div>
        </b-form>
        <template v-slot:footer>
            <b-button @click="showModal = false" variant="light" block>Cancel</b-button>
            <b-button @click="create('ok')" variant="primary"  block>Save</b-button>
        </template>
    </b-modal>
</template>

<script>
    export default {
        data() {
            return {
                currentUrl: window.location.origin,
                showModal: false,
                code: ''
            }
        },


        methods: {
            set(list) {
                this.code = list;
                this.showModal = true;
                this.fetchStatus();
            },
            fetchStatus() {
                axios.get(this.currentUrl + '/insights'+'/'+this.code+'/edit')
                    .then(response => {
                        
                    })
                    .catch(err => console.log(err));
            },
        }
    }
</script>
