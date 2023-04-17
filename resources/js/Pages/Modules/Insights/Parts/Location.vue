<template>
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h5 class="card-title mb-0 flex-grow-1">Provinces</h5>
            <div>
                <button class="btn btn-soft-primary btn-sm" type="button">
                    <div class="btn-content"> View all </div>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle table-centered table-nowrap mb-3">
                    <thead class="text-muted table-light fs-10">
                        <tr>
                            <th width="20%" scope="col">Province</th>
                            <th style="cursor: pointer;" class="text-center" v-for="(list,index) in locations.programs" v-bind:key="index">{{list.name}}</th>
                            <th width="10%" class="text-center">Total</th>
                        </tr>
                    </thead>
                    <tbody class="fs-11">
                        <tr v-for="(list,index) in locations.provinces" v-bind:key="index">
                            <td @click="view(list)" style="cursor: pointer;" width="20%" class="fw-medium">{{list.province}}</td>
                            <td :width="70/list.count.length+'%'" class="text-center" v-for="(count,index) in list.count" v-bind:key="index">{{count}} </td>
                            <td width="10%" class="text-center fw-bold">{{list.total}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<District ref="district"/>
</template>
<script>
import District from '../Modals/District.vue';
export default {
    components: { District },
    props: ['locations','total'],
    methods : {
        ucwords(str) {
            let str1 = str.toLowerCase().trim();
            return (str1 + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
                return $1.toUpperCase();
            });
        },
        percentage(data){
            return (_.divide(data, this.total)*100).toFixed(2)+'%';
        },
        view(list){
            this.$refs.district.set(list);
        }
    }
}
</script>