<template>
    <b-row class="g-2 mb-3 mt-n1">
        <b-col lg>
            <div class="input-group mb-1">
                <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                <input type="text" v-model="keyword" placeholder="Search scholar" class="form-control" style="width: 30%;">
                <select v-model="is_undergrad" @change="fetch()" class="form-select" id="inputGroupSelect01" style="width: 120px;">
                    <option value="all" selected>All</option>
                    <option value="1">Undergraduate</option>
                    <option value="0">JLSS</option>
                </select>
                <select v-model="program" @change="fetch()" class="form-select" id="inputGroupSelect01" style="width: 120px;">
                    <option :value="null" selected>Select Program</option>
                    <option :value="list.id" v-for="list in programs" v-bind:key="list.id">{{list.name}}</option>
                </select>
                <select v-model="status" @change="fetch()" class="form-select" id="inputGroupSelect02" style="width: 120px;">
                    <option :value="null" selected>Select Status</option>
                    <option :value="list.id" v-for="list in statuses" v-bind:key="list.id">{{list.name}}</option>
                </select>
                <input type="text" v-model="year" placeholder="Year Awarded" class="form-control" style="width: 100px;">
            </div>
        </b-col>
        <b-col lg="auto">
            <b-button type="button" variant="info" @click="show()">
                <i class="ri-filter-3-line align-bottom me-1"></i> Fliters
            </b-button>
        </b-col>
    </b-row>
    <div class="table-responsive">
        <table class="table table-nowrap align-middle mb-0">
            <thead class="table-light">
                <tr class="fs-11">
                    <th></th>
                    <th style="width: 30%;">Name</th>
                    <th style="width: 15%;" class="text-center">School</th>
                    <th style="width: 15%;" class="text-center">Program</th>
                    <th style="width: 15%;" class="text-center">Awarded Year</th>
                    <th style="width: 15%;" class="text-center">Status</th>
                    <th style="width: 10%;"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="user in lists" v-bind:key="user.id" :class="[(user.is_completed == 0) ? 'table-warnings' : '']">
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 chat-user-img online user-own-img align-self-center me-3 ms-0">
                                <img :src="currentUrl+'/images/avatars/'+user.profile.avatar" class="rounded-circle avatar-xs" alt="">
                                <span class="user-status" :style="(user.profile.gender == 'Male') ? 'background-color: #5cb0e5;' : 'background-color: #e55c7f;'"></span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <h5 class="fs-13 mb-0 text-dark">{{user.profile.lastname}}, {{user.profile.firstname}} {{user.profile.middlename[0]}}.</h5>
                        <p class="fs-12 text-muted mb-0">{{user.spas_id}}</p>
                    </td>
                    <td class="text-center">
                        <p class="fs-12 mb-n1 text-dark">{{(user.education.school instanceof Object) ? user.education.school.name : user.education.school}}</p>
                        <p class="fs-12 text-muted mb-0">{{(user.education.course instanceof Object) ? user.education.course.name : user.education.course}}</p>
                    </td>
                    <td class="text-center">{{user.program.name}}</td>
                    <td class="text-center">{{user.awarded_year}}</td>
                    <td class="text-center">
                        <span v-if="user.status.type == 'ongoing'" :class="'badge bg-'+user.status.color">{{user.status.name}}</span>
                        <span v-else :class="'badge bg-soft-'+user.status.color+' badge-soft-'+user.status.color">{{user.status.name}}</span>
                    </td>
                    <td class="text-end">
                        <b-button v-if="user.user == null" @click="authenticate(user)" variant="soft-primary" v-b-tooltip.hover title="Create Scholar Account" size="sm" class="edit-list me-1"><i class="ri-user-add-fill align-bottom"></i> </b-button>
                        <b-button v-if="user.is_completed == 0" @click="update(user)" variant="soft-warning" v-b-tooltip.hover title="Update" size="sm" class="remove-list me-1"><i class="ri-error-warning-fill align-bottom"></i></b-button>
                        <Link v-if="user.is_completed == 1" :href="`/scholars/${user.code}`"><b-button variant="soft-info" v-b-tooltip.hover title="View" size="sm" class="remove-list me-1"><i class="ri-eye-fill align-bottom"></i></b-button></Link>
                        <!-- <b-button variant="soft-primary" v-b-tooltip.hover title="Edit" size="sm" class="edit-list"><i class="ri-pencil-fill align-bottom"></i> </b-button> -->
                    </td>
                </tr>
            </tbody>
        </table>
        <Pagination class="ms-2 me-2" v-if="meta" @fetch="fetch" :lists="lists.length" :links="links" :pagination="meta" />
    </div>
    <Filter :regions="regions" :programs="programs" @status="filter" ref="filter"/>
    <Update ref="update" :dropdowns="dropdowns"/>
    <Authentication ref="authentication"/>
</template>
<script>
import Update from './Modals/Update.vue';
import Filter from './Modals/Filter.vue';
import Authentication from './Modals/Authentication.vue';
import Pagination from "@/Shared/Components/Pagination.vue";
export default {
    props : ['regions', 'programs', 'dropdowns'],
    components: { Pagination, Filter, Update, Authentication },
    data(){
        return {
            currentUrl: window.location.origin,
            lists: [],
            meta: {},
            links: {},
            arr: {},
            status: null,
            program: null,
            year: null,
            count2: null,
            is_undergrad: 'all',
            sorty: 'asc',
            keyword: '',
        }
    },
    created() {
        this.fetch();
    },
    watch: {
        keyword(newVal){
            this.checkSearchStr(newVal)
        },
        year(newVal){
            this.checkSearchStr(newVal)
        },
        datares: {
            deep: true,
            handler(val = null) {
                if(val != null && val !== ''){
                    this.message(val.data);
                }
            },
        },
    },
    computed: {
        statuses : function() {
            return this.dropdowns.filter(x => x.classification === 'Status');
        },
        datares() {
            return this.$page.props.flash.datares;
        },
    },
    methods: {
        checkSearchStr: _.debounce(function(string) {
            this.fetch();
        }, 300),
        fetch(page_url) {
            let info = {
                'keyword': this.keyword,
                'status': (this.status == null) ? null : this.status,
                'program': (this.program == null) ? null : this.program,
                'year': (this.year === '' || this.year == null) ? '' : this.year,
                'is_undergrad': this.is_undergrad,
                'counts': ((window.innerHeight-350)/56),
                'sorty': this.sorty
            };

            info = (Object.keys(info).length == 0) ? '-' : JSON.stringify(info);
            let filter = (Object.keys(this.arr).length == 0) ? '-' : JSON.stringify(this.arr);

            page_url = page_url || '/scholars';
            axios.get(page_url, {
                params: {
                    info: info,
                    filter: filter,
                    search: true
                }
            })
            .then(response => {
                this.lists = response.data.data;
                this.meta = response.data.meta;
                this.links = response.data.links;
            })
            .catch(err => console.log(err));
        },
        show(){
            this.$refs.filter.show();
        },
        update(data){
            this.$refs.update.show(data);
        },
        authenticate(data){
            this.$refs.authentication.show(data);
        },
        filter(list){
            this.arr = list;
            this.fetch();
        },
        message(data){
            let index = this.lists.findIndex(u => u.id === data.id);
            this.lists[index] = data;
        },
    }
}
</script>
