<template>
    <b-row class="g-2 mb-3 mt-n1">
        <b-col lg>
            <div class="input-group mb-1">
                <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                <input type="text" v-model="keyword" placeholder="Search.." class="form-control" style="width: 40%;">
            </div>
        </b-col>
        <b-col lg="auto">
            <b-button @click="create()" type="button" variant="danger">Create</b-button>
        </b-col>
    </b-row>
    <div class="table-responsive">
        <table class="table table-nowrap align-middle mb-0">
            <thead class="table-light">
                <tr class="fs-11">
                    <th style="width: 15%;">Code</th>
                    <th style="width: 15%;" class="text-center">Check #</th>
                    <th style="width: 15%;" class="text-center">Amount</th>
                    <th style="width: 20%;" class="text-center">Check Date</th>
                    <th style="width: 20%;" class="text-center">Credited Date</th>
                    <th style="width: 15%;" class="text-center">Added By</th>
                    <th style="width: 15%;"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(list,index) in lists" v-bind:key="list.id">
                    <td>{{list.code}}</td>
                    <td class="text-center fs-12 fw-medium">{{list.check_no}}</td>
                    <td class="text-center">₱{{formatMoney(list.total)}}</td>
                    <td class="text-center">{{list.credited_at}}</td>
                    <td class="text-center">{{list.checked_at}}</td>
                    <td class="text-center">{{list.added_by}}</td>
                    <td class="text-end">
                        <b-button  @click="view(list,index)" variant="soft-primary" v-b-tooltip.hover title="View Lists" size="sm" class="edit-list"><i class="ri-add-fill    align-bottom"></i> </b-button>
                    </td>
                </tr>
            </tbody>
        </table>
        <Pagination class="ms-2 me-2" v-if="meta" @fetch="fetch" :lists="lists.length" :links="links" :pagination="meta" />
    </div>
    <Create ref="create" @info="fetch"/>
    <View :expenses="expenses" @total="update" ref="view"/>
</template>
<script>
import Create from './Create.vue';
import View from './View.vue';
import Pagination from "@/Shared/Components/Pagination.vue";
import Layout from "@/Shared/Layout/Index";
import profile from "@/Pages/Modules/Accounting/Index";
export default {
    components : { Pagination, Create, View },
    layout: (h,page) => {
        return h(Layout, [
            h(profile,[page])
        ])
    },
    layout: [Layout, profile],
    props: ['expenses'],
    data(){
        return {
            currentUrl: window.location.origin,
            lists: [],
            meta: {},
            links: {},
            keyword: '',
            index: ''
        }
    },
    created(){
        this.fetch();
    },  
    computed: {
        datares() {
            return this.$page.props.flash.datares;
        },
    },
    watch: {
        keyword(newVal){
            this.checkSearchStr(newVal)
        },
        datares: {
            deep: true,
            handler(val = null) {
                if(val != null && val !== ''){
                    this.fetch();
                }
            },
        },
    },
    methods: {
        checkSearchStr: _.debounce(function(string) {
            this.fetch();
        }, 300),
        create(){
            this.$refs.create.set();
        },
        fetch(page_url) {
            page_url = page_url || '/accounting';
            axios.get(page_url, {
                params: {
                    keyword: this.keyword,
                    type: 'allotments',
                    counts: ((window.innerHeight-350)/56),
                    lists: true
                }
            })
            .then(response => {
                this.lists = response.data.data;
                this.meta = response.data.meta;
                this.links = response.data.links;
            })
            .catch(err => console.log(err));
        },
        view(data,index){
            this.index = index;
            this.$refs.view.set(data);
        },
        update(val){
            this.lists[this.index].total =  parseInt(this.lists[this.index].total) +  parseInt(val);
        },
        formatMoney(value) {
            let val = (value/1).toFixed(2).replace(',', '.')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        },
    }
}
</script>