<template>
    <b-row>
        <b-col lg="4">
            <b-card>
                <b-card-body style="height: calc(100vh - 355px)">
                    <b-card no-body class="mb-1 ribbon-box ribbon-fill ribbon-sm">
                        <div class="ribbon ribbon-success"><i class="ri-flashlight-fill"></i></div>
                        <b-card-body>
                            <b-link class="d-flex align-items-center" role="button" v-b-toggle.contactInitiated1>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="fs-14 mb-1">{{semester.academic_year}}</h6>
                                    <p class="text-muted mb-0">{{semester.start_at}} - {{semester.end_at}}</p>
                                </div>
                            </b-link>
                        </b-card-body>
                    </b-card>
                    
                </b-card-body>
            </b-card>
        </b-col>
        <b-col lg="8">

            <div class="card" id="contactList">
                <div class="card-header">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="search-box"><input type="text" class="form-control search" placeholder="Search for semester">
                                <i class="ri-search-line search-icon"></i>
                            </div>
                        </div>
                        <div class="col-md-auto ms-auto">
                            <div class="d-flex align-items-center gap-2">
                                <button @click="addnew" class="btn btn-danger btn-md add-btn" type="button">
                                    <div class="btn-content">New Semester</div>
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
                                        <th>Academic Year</th>
                                        <th class="text-center">Start at</th>
                                        <th class="text-center">End at</th>
                                        <th class="text-center">Semester</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-end"></th>
                                    </tr>
                                </thead>
                                <tbody class="list form-check-all">
                                    <tr v-for="list in lists" v-bind:key="list.id">
                                        <td class="fs-14 fw-medium">{{list.academic_year}}</td>
                                        <td class="text-center">{{list.start_at}}</td>
                                        <td class="text-center">{{list.end_at}}</td>
                                        <td class="text-center">{{list.semester.name}}</td>
                                        <td class="text-center">
                                            <span v-if="list.is_active" class="badge bg-success">Active</span>
                                            <span v-else class="badge bg-danger">Inactive</span>
                                        </td>
                                        <td class="text-end">
                                            <b-button variant="soft-primary" v-b-tooltip.hover title="Edit" size="sm" class="edit-list"><i class="ri-pencil-fill align-bottom"></i> </b-button>
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
    <New :semesters="semesters" @status="message" ref="new"/>
</template>
<script>
import Pagination from "@/Shared/Components/Pagination.vue";
import New from './Modals/Semester.vue';
export default {
    components: { New, Pagination },
    props: ['dropdowns','id','term'],
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
            semester: {}
        }
    },
    created(){
        this.fetch();
        this.fetchSemester();
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
                    type: 'semesters',
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
        fetchSemester(){
            axios.get(this.currentUrl+'/schools/'+this.id+'/edit',{
                params : {
                    type: 'semester'
                }
            })
            .then(response => {
                if(response){
                    this.semester = response.data.data;   
                }
            })
            .catch(err => console.log(err));
        },
        message(){
            this.fetch();
        }
    }
}
</script>
