<template>
    <Head title="Reimbursements" />
    <PageHeader :title="title" :items="items" />
    <div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1">
        <div class="file-manager-sidebar">
            <div class="p-4 d-flex flex-column h-100">
                <div class="d-grid gap-2">
                    <b-button @click="create()" variant="primary" block>Create</b-button>
                </div>
            </div>
        </div>
        <div class="file-manager-content w-100 p-4 pb-0" style="height: calc(100vh - 180px)" ref="box">
            <b-row class="g-2 mb-3 mt-n1">
                <b-col lg>
                    <div class="input-group mb-1">
                        <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                        <input type="text" v-model="keyword" placeholder="Search.." class="form-control" style="width: 40%;">
                        <select  v-model="status" @change="fetch()" placeholder="Choose Level" class="form-select" style="width: 20px;">
                            <option :value="null" selected>Select Status</option>
                            <option value="true">Approved</option>
                            <option value="false">Pending</option>
                        </select>
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
                            <th style="width: 20%;">Scholar</th>
                            <th style="width: 20%;" class="text-center">Semester</th>
                            <th style="width: 20%;" class="text-center">Benefit</th>
                            <th style="width: 15%;" class="text-center">Amount</th>
                            <th style="width: 15r%;" class="text-center">Status</th>
                            <th style="width: 10%;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(list,index) in lists" v-bind:key="list.id">
                            <td class="fs-12 fw-medium">{{list.scholar.lastname}}, {{list.scholar.firstname}}</td>
                            <td class="text-center">{{list.semester.academic_year}} ({{list.semester.semester.name}})</td>
                            <td class="text-center">{{list.benefit.name}}</td> 
                            <td class="text-center">₱{{formatMoney(list.amount)}}</td>
                            <td class="text-center">
                                <span v-if="list.is_approved" class="badge bg-success">Approved</span>
                                <span v-else class="badge bg-warning">Pending</span>
                            </td>
                            <td class="text-end">
                                <b-button  @click="view(list,index)" variant="soft-primary" v-b-tooltip.hover title="View" size="sm" class="edit-list"><i class="ri-eye-fill align-bottom"></i> </b-button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <Pagination class="ms-2 me-2" v-if="meta" @fetch="fetch" :lists="lists.length" :links="links" :pagination="meta" />
            </div>
        </div>
    </div>
    <Create :privileges="privileges" ref="create"/>
    <View ref="view"/>
</template>
<script>
import View from './Modals/View.vue';
import Create from './Modals/Create.vue';
import Pagination from "@/Shared/Components/Pagination.vue";
import PageHeader from "@/Shared/Components/PageHeader.vue";
export default {
    props: ['privileges'],
    components: { PageHeader, Create, Pagination, View },
    data() {
        return {
            currentUrl: window.location.origin,
            title: "Reimbursements",
            items: [{text: "Reimbursements",href: "/",}, {text: "List",active: true, }, ],
            lists: [],
            meta: {},
            links: {},
            keyword: '',
            status: null
        };
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
        datares: {
            deep: true,
            handler(val = null) {
                if(val != null && val !== ''){
                    this.fetch();
                }
            },
        },
    },
    methods : {
        create(){
            this.$refs.create.set();
        },
        checkSearchStr: _.debounce(function(string) {
            this.fetch();
        }, 300),
        fetch(page_url) {
            page_url = page_url || '/reimbursements';
            axios.get(page_url, {
                params: {
                    keyword: this.keyword,
                    search: 'lists',
                    status: this.status,
                    counts: ((window.innerHeight-350)/56),
                }
            })
            .then(response => {
                this.lists = response.data.data;
                this.meta = response.data.meta;
                this.links = response.data.links;
            })
            .catch(err => console.log(err));
        },
        formatMoney(value) {
            let val = (value/1).toFixed(2).replace(',', '.')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        },
        view(data){
            this.$refs.view.set(data);
        }
    }
}
</script>
<style>
    .file-manager-sidebar {
        min-width: 450px;
        max-width: 450px;
        height: calc(100vh - 180px);
    }
    .dropdown-menu-lg {
        width: 89%;
    }
</style>

