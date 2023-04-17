<template>
    <b-row class="g-2 mb-3 mt-n1" v-if="!show">
        <b-col lg="12">
            <div class="input-group mb-1">
                <label class="input-group-text bg-light"> <i class='bx bx-search-alt'></i></label>
                <input type="text" class="form-control" style="width: 25%;" placeholder="Search..." v-model="keyword" @keyup="fetch()"/>
                <select  v-model="level" @change="fetch()" placeholder="Choose Level" class="form-select" :class="(term == null) ? 'text-muted' : ''" style="width: 120px;">
                    <option :value="null" selected>Select Month</option>
                    <option :value="m" v-for="(m,index) in months" v-bind:key="index">{{ m }}</option>
                </select>
                <input type="text" class="form-control" style="width: 120px;" placeholder="Enter Year" v-model="year"  @keyup="fetch()"/>
            </div>
        </b-col>
        <b-col lg="12">
            <div class="table-responsive">
                <table class="table table-nowrap align-middle mb-0">
                    <thead class="table-light">
                        <tr class="fs-11">
                            <th>Release Month</th>
                            <th class="text-center">Receiver</th>
                            <th class="text-center">Total</th>
                            <th class="text-center">Added By</th>
                            <th class="text-center">Created At</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="list in lists" v-bind:key="list.id">
                            <td>
                                <h5 class="fs-14 mb-0 text-dark">{{list.month}}</h5>
                                <p class="fs-12 text-muted mb-0">Release no.{{list.number }}</p>
                            </td>
                            <td class="text-center">{{list.lists.length}} scholars</td>
                            <td class="text-center"> ₱{{ formatMoney(list.total) }}</td>
                            <td class="text-center">{{list.added_by.firstname}} {{list.added_by.lastname}}</td>
                            <td class="text-center">{{list.created_at}}</td>
                            <td class="text-end">
                                <b-button @click="view(list)" variant="soft-info" v-b-tooltip.hover title="View" size="sm" class="edit-list me-1"><i class="ri-eye-fill align-bottom"></i> </b-button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <Pagination class="ms-2 me-2" v-if="meta" @fetch="fetch" :lists="lists.length" :links="links" :pagination="meta" />
            </div>
        </b-col>
    </b-row>
    <div v-else>
        <b-row class="mb-3 mt-n2">
            <div class="col-md-6">
                <div class="page-title-left mt-2">
                    <ol class="breadcrumb m-0 fs-15">
                        <li class="breadcrumb-item fw-bold">View Releasing</li>
                    </ol>
                </div> 
            </div>
            <div class="col-md-6">
                <div class="hstack float-end gap-2 ">
                    <button @click="show = false" class="btn btn-danger btn-sm mt-2 mb-n2" type="button">
                        <div class="btn-content"> Close </div>
                    </button>
                </div>
            </div>
        </b-row>
        <hr/>
        <b-row>
            <div class="responsive">
                <table class="table table-centered table-bordered table-nowrap">
                    <thead class="thead-light align-middle text-center">
                        <tr class="fw-bold fs-13 text-primary">
                            <td colspan="2">{{selected.month.toUpperCase() }} RELEASE NO.{{selected.number}} <span class="fs-12 fw-medium text-muted">({{selected.created_at}})</span></td>
                        </tr>
                        <tr class="fw-bold font-size-11">
                            <td>No. of Scholars</td>
                            <td>Total</td>
                        </tr>
                    </thead>
                    <tbody class="align-middle text-center">
                        <tr>
                            <td width="50%">{{selected.lists.length}} </td>
                            <td width="50%">₱ {{ formatMoney(selected.total)}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="table-responsive">
                <table class="table table-nowrap align-middle mb-0">
                    <thead class="table-light">
                        <tr class="fs-11">
                            <th>Name</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="table-responsive" data-simplebar style="max-height: 300px;">
                <table class="table table-nowrap align-middle mb-0">
                    <tbody>
                        <tr v-for="l in selected.lists" v-bind:key="l.id">
                            <td>{{l.name}}</td>
                            <td class="text-end">₱{{ formatMoney(l.total) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </b-row>
    </div>
    
    <Lists ref="lists"/>
</template>
<script>
import Lists from '../Modals/Lists.vue';
import Pagination from "@/Shared/Components/Pagination.vue";
export default {
    components: { Pagination, Lists },
    data(){
        return {
            currentUrl: window.location.origin,
            lists: [],
            meta: {},
            links: {},
            months: ['January','February','March','April','May','June','July','August','September','October','November','December'],
            keyword: '',
            month: null,
            year: '',
            show: false,
            selected: ''
        }
    },
    created(){
        this.fetch();
    },
    methods : {
        fetch(page_url){
            page_url = page_url || '/financial-benefits';
            axios.get(page_url,{
                params : {
                    keyword : this.keyword,
                    count: Math.floor((window.innerHeight - 320) / 60),
                    month: this.month,
                    year: this.year,
                    search: true
                }
            })
            .then(response => {
                if(response){
                    this.lists = response.data.data;
                    this.meta = response.data.meta;
                    this.links = response.data.links;
                }
            })
            .catch(err => console.log(err));
        },
        formatMoney(value) {
            let val = (value/1).toFixed(2).replace(',', '.')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        },
        view(data){
            this.show = true;
            this.selected = data;
        }
    }
}
</script>