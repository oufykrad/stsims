<template>
    <b-modal v-model="showModal" title="View Benefits" style="--vz-modal-width: 600px;" hide-footer header-class="p-3 bg-light" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>    
        <b-form class="customform mb-2">
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3 mt-2">
                         <div class="col-md-12 border-bottom">
                            <div class="d-flex mb-3">
                                <div class="flex-shrink-0 me-3">
                                    <img :src="currentUrl+'/images/avatars/'+scholar.avatar" alt="" class="avatar-sm rounded-circle img-thumbnail">
                                </div>
                                <div class="flex-grow-1 align-self-center">
                                    <div class="text-muted">
                                        <h5 class="mb-0 fs-14">{{ scholar.name }}</h5>
                                        <p class="mb-0 fs-12">{{ scholar.program }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-2 mt-2">
                            <div class="col-lg-12">
                                <div data-simplebar style="max-height: 215px;">
                                    <b-list-group>
                                        <b-list-group-item class="list-group-item" v-for="(list,index) in scholar.benefits" v-bind:key="index">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1">
                                                    <div class="d-flex">
                                                        <div class="flex-shrink-0 ms-2">
                                                            <h6 class="fs-14 mb-0">{{list.benefit.name}}</h6>
                                                            <small class="text-muted">{{list.month}}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <span class="text-danger fw-medium">₱{{ formatMoney(list.amount) }}</span>
                                                </div>
                                            </div>
                                        </b-list-group-item>
                                    </b-list-group>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </b-form>
    </b-modal>
</template>

<script>
    export default {
        data() {
            return {
                currentUrl: window.location.origin,
                scholar: '',
                showModal: false
            }
        },

        methods: {
            set(data) {
                this.scholar = data;
                this.showModal = true;
            },
            formatMoney(value) {
                let val = (value/1).toFixed(2).replace(',', '.')
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },
        }
    }
</script>
