<template>
    <b-modal v-model="showModal" title="View Benefits" size="lg" hide-footer header-class="p-3 bg-light" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>    
        <div data-simplebar style="max-height: 300px;">
            <b-accordion class="custom-accordionwithicon" id="accordionWithicon">
                <b-accordion-item :title="list.name" v-for="(list,index) in info.lists" v-bind:key="index">
                    <div class="table-responsive">
                        <table class="table table-nowrap align-middle mb-0">
                            <thead class="table-light">
                                <tr class="fs-11">
                                    <th>Name</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody >
                                <tr v-for="l in list.benefits" v-bind:key="l.id">
                                    <td>{{l.benefit.name}}</td>
                                    <td class="text-end">
                                        {{l.amount}}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </b-accordion-item>
            </b-accordion>
        </div>
    </b-modal>
</template> 

<script>
export default {
    data(){
        return {
            info: '',
            showModal: false
        }
    },
    methods : {
        set(data){
            this.info = data;
            this.showModal = true;
        }
    }
}
</script>