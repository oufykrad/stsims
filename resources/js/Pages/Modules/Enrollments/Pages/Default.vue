<template>
    <b-row class="g-2 mb-3 mt-n1">
        <b-col lg>
            <div class="input-group mb-1">
                <label class="input-group-text bg-light"> <i class='bx bx-search-alt'></i></label>
                <input type="text" class="form-control" style="width: 25%;" placeholder="Search..." v-model="keyword" @keyup="fetch()"/>
                <select  v-model="term" @change="fetch()" placeholder="Choose Semester" class="form-select" :class="(term == null) ? 'text-muted' : ''" style="width: 120px;">
                    <option :value="null" selected>Select Type</option>
                    <option :value="m" v-for="(m,index) in terms" v-bind:key="index">{{ m.name }}</option>
                </select>
                <select  v-model="semester" @change="fetch()" placeholder="Choose Semester" class="form-select" :class="(term == null) ? 'text-muted' : ''" style="width: 120px;">
                    <option :value="null" selected>Select Semester</option>
                    <option :value="m" v-for="(m,index) in semesters" v-bind:key="index">{{ m.name }}</option>
                </select>
                <select  v-model="level" @change="fetch()" placeholder="Choose Level" class="form-select" :class="(term == null) ? 'text-muted' : ''" style="width: 120px;">
                    <option :value="null" selected>Select Level</option>
                    <option :value="m" v-for="(m,index) in levels" v-bind:key="index">{{ m.others }}</option>
                </select>
                <input type="text" class="form-control" style="width: 120px;" placeholder="Enter Year" v-model="year"  @keyup="fetch()"/>
            </div>
        </b-col>
    </b-row>
    <div class="table-responsive">
        <table class="table table-nowrap align-middle mb-0">
            <thead class="table-light">
                <tr class="fs-11">
                    <th>Academic Year</th>
                    <th class="text-center">Scholar</th>
                    <th class="text-center">School</th>
                    <th class="text-center">Enrolled At</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="list in lists" v-bind:key="list.id">
                    <td>
                        <h5 class="fs-13 mb-0 text-dark">{{list.academic_year}}</h5>
                        <p class="fs-11 text-muted mb-0">{{list.semester }}</p>
                    </td>
                    <td class="text-center">{{list.scholar}}</td>
                    <td class="text-center">{{list.school}}</td>
                    <td class="text-center">{{list.enrolled_at}}</td>
                    <td class="text-end">
                        <b-button @click="view(list)" variant="soft-info" v-b-tooltip.hover title="View" size="sm" class="edit-list me-1"><i class="ri-eye-fill align-bottom"></i> </b-button>
                    </td>
                </tr>
            </tbody>
        </table>
        <Pagination class="ms-2 me-2" v-if="meta" @fetch="fetch" :lists="lists.length" :links="links" :pagination="meta" />
    </div>
    <View ref="view"/>
</template>
<script>    
import View from '../Modals/View.vue';
import Pagination from "@/Shared/Components/Pagination.vue";
export default {
    props: ['dropdowns'],
    components: { Pagination, View },
    data(){
        return {
            currentUrl: window.location.origin,
            pagination: {},
            meta: {},
            links: {},
            lists: [],
            keyword: '',
            term: null,
            semester: null,
            level: null,
            year: ''
        }
    },

    computed:{
        terms : function() {
            return this.dropdowns.filter(x => x.classification === 'Term Type');
        },
        semesters : function() {
            return this.dropdowns.filter(x => x.type === 'term');
        },
        levels : function() {
            return this.dropdowns.filter(x => x.classification === 'Level');
        }
    },

    created(){
        this.fetch();
    },  

    methods: {
        fetch(page_url){
            page_url = page_url || '/enrollments';
            axios.get(page_url,{
                params: {  
                    counts: Math.floor((window.innerHeight - 320) / 60),
                    search: true,
                    type: 'enrolled'
                }
            })
            .then(response => {
                this.lists = response.data.data;
                this.meta = response.data.meta;
                this.links = response.data.links;
            })
            .catch(err => console.log(err));
        },
        view(data){
            this.$refs.view.set(data);
        }

    }
}
</script>