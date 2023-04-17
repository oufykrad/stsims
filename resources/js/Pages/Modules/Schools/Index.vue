<template>
<Head title="Students" />
<PageHeader :title="title" :items="items" />
    <div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1">

        <div class="file-manager-sidebar">
            <Settings :agency="agency" :count="count" :total="meta.total" :overall="overall"/>
        </div>
            
        <div class="file-manager-content w-100 p-4 pb-0" style="height: calc(100vh - 180px)">
             <div class="p-3 bg-light rounded mb-2">
                <b-row class="g-2">
                    <b-col lg="auto">
                        <select class="form-control" v-model="region">
                            <option value="">All Region</option>
                            <option :value="list.id" v-for="(list, index) of regions" :key="index">{{list.region}}</option>
                        </select>
                    </b-col>
                    <b-col lg>
                        <div class="search-box">
                            <input type="text" v-model="keyword" id="searchTaskList" class="form-control search" placeholder="Search school">
                            <i class="ri-search-line search-icon"></i>
                        </div>
                    </b-col>
                </b-row>
            </div>

            <div class="todo-content position-relative px-4 mx-n4" id="todo-content">
                <div id="elmLoader" v-if="lists.length == 0">
                    <h5 class="mt-4">Sorry! No Result Found</h5>
                </div>
                <div class="todo-task" id="todo-task" v-else>
                    <div class="table-responsive">
                        <table class="table align-middle position-relative table-nowrap">
                            <thead class="table-active">
                                <tr>
                                    <th width="2%"></th>
                                    <th scope="col">Name</th>
                                    <th scope="col" class="text-center">Class</th>
                                    <th scope="col" class="text-center">Term</th>
                                    <th scope="col" class="text-center">Grading</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody id="task-lists">
                                <tr v-for="(list, index) of lists" :key="index">
                                    <td>
                                        <div class="avatar-xs">
                                            <span v-if="list.avatar == 'school.jpg'" :class="'avatar-title rounded-circle bg-primary text-white'">{{list.name[0]}}</span>
                                            <img v-else :src="currentUrl+'/images/avatars/'+list.avatar" alt="" class="avatar-xs rounded-circle">
                                        </div>
                                        
                                    </td>
                                     <td>
                                    <h5 class="fs-14 mb-0 text-dark">{{list.name}} - {{list.campus}}</h5>
                                    <p class="fs-12 text-muted mb-0">{{list.address}}, {{list.municipality.name}}</p>
                                </td>
                                <td class="text-center">{{list.class.name}}</td>
                                <td class="text-center">{{list.term.name}}</td>
                                <td class="text-center">{{list.grading.name}}</td>
                                <td class="text-end">
                                    <Link :href="`/schools/${list.code}`">
                                        <button class="btn btn-soft-primary btn-sm" type="button">
                                            <div class="btn-content"> View </div>
                                        </button>
                                    </Link>
                                </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Settings from './Settings.vue';
import Multiselect from '@suadelabs/vue3-multiselect';
import PageHeader from "@/Shared/Components/PageHeader.vue";
export default {
    props : ['regions','agency', 'count'],
    components : { PageHeader, Multiselect, Settings },
    data() {
        return {
            currentUrl: window.location.origin,
            title: "List of Schools",
            items: [{text: "Lists",href: "/",},{text: "School",active: true,},],
            lists : [],
            meta: {},
            links: {},
            keyword: '',
            region: '',
            overall: 0
        };
    },
    created(){
        this.fetch();
        this.countSync();
    },
    watch: {
        region(newVal) {
            this.fetch();
        },
        keyword(newVal) {
            this.checkSearchStr(newVal);
        },
    },
    methods : {
        checkSearchStr: _.debounce(function(string) {
            this.fetch();
        }, 300),
        add(){
            this.$refs.new.show();
        },
        fetch(page_url){
            page_url = page_url || '/schools';
            axios.get(page_url,{
                params : {
                    keyword : this.keyword,
                    count: this.count,
                    region: this.region,
                    search: true
                }
            })
            .then(response => {
                if(response){
                    this.lists = response.data.data;                    
                    this.meta = response.data.meta;
                    this.links = response.data.links;
                    this.page = this.meta.per_page*(this.meta.current_page - 1);
                }
            })
            .catch(err => console.log(err));
        },
        countSync() {
            axios.get(this.currentUrl + '/sync/schools/count')
            .then(response => {
                this.overall = response.data;
            })
            .catch(err => console.log(err));
        },
        update(type,data){
            this.$refs.update.show(type,data);
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
</style>
