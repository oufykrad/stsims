<template>
    <b-row v-if="!courseView">
        <b-col lg="4">
            <b-card>
                <b-card-body style="height: calc(100vh - 355px)">

                </b-card-body>
            </b-card>
        </b-col>
        <b-col lg="8">
            <div class="card" id="contactList">
                <div class="card-header">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="search-box"><input type="text" class="form-control search" placeholder="Search for course">
                                <i class="ri-search-line search-icon"></i>
                            </div>
                        </div>
                        <div class="col-md-auto ms-auto">
                            <div class="d-flex align-items-center gap-2">
                                <button @click="addnew" class="btn btn-danger btn-md add-btn" type="button">
                                    <div class="btn-content">New Course</div>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body" style="height: calc(100vh - 392px)">
                    <div>
                        <div class="table-responsive mb-3">
                            <table class="table align-middle table-nowrap mb-0">
                                <thead class="table-light fs-12">
                                    <tr>
                                        <th>Name</th>
                                        <th class="text-center">Certification</th>
                                        <th class="text-center">Validity</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-end"></th>
                                    </tr>
                                </thead>
                                <tbody class="list form-check-all">
                                    <tr v-for="list in lists" v-bind:key="list.id">
                                        <td class="fs-14 fw-medium">{{list.course.name}}</td>
                                        <td class="text-center">{{list.certification}}</td>
                                        <td class="text-center">{{list.validity}}</td>
                                        <td class="text-center">
                                            <span v-if="list.is_active" class="badge bg-success">Active</span>
                                            <span v-else class="badge bg-danger">Inactive</span>
                                        </td>
                                        <td class="text-end">
                                            <b-button @click="view(list)" variant="soft-primary" v-b-tooltip.hover title="View" size="sm" class="edit-list"><i class="ri-eye-fill align-bottom"></i> </b-button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                             <Pagination class="ms-2 me-2" v-if="meta" @fetch="fetch" :lists="lists.length" :links="links" :pagination="meta" />
                        </div>
                        
                    </div>
                </div>
            </div>
        </b-col>
    </b-row>

    <b-row v-else>
        <b-col lg="4">
            <b-card>
                <b-card-body style="height: calc(100vh - 355px)">
                    <div class="col-md">
                        <h4 class="fw-semibold">{{course.course.name}}</h4>
                        <div class="hstack gap-3 flex-wrap">
                            <div class="text-muted"><i class="mdi mdi-school-outline align-bottom me-1"></i>{{ (course.course.abbreviation) ? course.course.abbreviation : 'Not set'}}</div>
                            <div class="vr"></div>
                            <div class="text-muted"><i class="ri-file-list-3-line align-bottom me-1"></i>Certification: <span class="fw-medium">{{course.certification}}</span></div>
                            <div class="vr"></div>
                            <div class="text-muted"><i class="ri-calendar-todo-line align-bottom me-1"></i>Validity: <span class="fw-medium">{{course.validity}}</span></div>
                        </div>
                    </div>
                    <hr />
                    <div class="d-flex align-items-center mb-3">
                        <span class="flex-grow-1 text-muted fs-14 mb-0"> List of Prospectus</span>
                        <div class="flex-shrink-0">
                            <button @click="newProspectus(course)" class="btn btn-soft-primary btn-sm text-nowrap flex-shrink-0" type="button">
                                <div class="btn-content"><i class="ri-add-line align-bottom me-1"></i> New</div>
                            </button>
                        </div>
                    </div>
                    <hr />
                    <div id="external-events" v-if="course.prospectuses.length > 0">
                        <div @click="openProspectus(list,course)" style="cursor: pointer;" v-for="(list, index) of course.prospectuses" :key="index" class="external-event fc-event" :class="(list.is_active) ? 'bg-soft-success text-success' : 'bg-soft-primary text-primary'">
                            <i class="mdi mdi-checkbox-blank-circle font-size-11 me-2"></i>Prospectus {{ list.school_year}} 
                        </div>
                    </div>
                    <div v-else class="alert alert-light ale alert-dismissible alert-solid alert-label-icon" role="alert">
                        <i class="ri-file-warning-line  label-icon"></i>No <strong>Prospectus</strong> found. Add now
                    </div>
                </b-card-body>
            </b-card>
        </b-col>
        <b-col lg="8">
            <b-card>
                <b-card-body style="height: calc(100vh - 355px)">

                </b-card-body>
            </b-card>
        </b-col>
    </b-row>
    <Prospectus :type="term" @status="update" ref="prospectus"/>
    <ProspectusView ref="view"/>
</template>
<script>
import Prospectus from './Modals/Prospectus.vue';
import ProspectusView from './Modals/ProspectusView.vue';
import Pagination from "@/Shared/Components/Pagination.vue";
export default {
    components: { Pagination, Prospectus, ProspectusView },
    props: ['id','term'],
    computed : {
        semesters : function() {
            return this.dropdowns.filter(x => x.classification === this.term);
        }
    },
    data(){
        return{
            currentUrl: window.location.origin,
            lists: [],
            meta: {},
            links: {},
            course: null,
            courseView: false
        }
    },
    watch: {
        datares: {
            deep: true,
            handler(val = null) {
                if(val != null && val !== ''){
                    this.update(val);
                }
            },
        },
    },
    computed: {
        datares() {
            return this.$page.props.flash.datares;
        },
    },
    created(){
        this.fetch();
    },
    methods : {
        addnew(){
            this.$refs.new.show(this.id);
        },
        fetch(page_url){
            page_url = page_url || '/schools/'+this.id+'/edit';
            axios.get(page_url,{
                params : {
                    keyword : this.keyword,
                    type: 'courses',
                    counts: ((window.innerHeight-500)/51)
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
        message(){
            this.fetch();
        },
        view(data){
            this.course = data;
            this.courseView = true;
        },
        openProspectus(prospectus,course){
            this.$refs.view.set(prospectus,course);
        },
        newProspectus(data){
            this.$refs.prospectus.show(data);
        },
        update(data){
            this.course.prospectuses.unshift(data);
        }
    }
}
</script>
