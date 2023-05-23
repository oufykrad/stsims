<template>
    <Head title="Monitoring" />
    <PageHeader :title="title" :items="items" />
    <div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1">
         <div class="file-manager-sidebar">
            <div class="p-4 d-flex flex-column h-100">

                <h6 class="fs-11 text-muted text-uppercase mb-3">Scholars Enrolled</h6>
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="ri-team-fill text-primary fs-17"></i>
                    </div>
                    <div class="flex-grow-1 ms-3 overflow-hidden">
                        <div class="progress mb-2 progress-sm">
                            <div class="progress-bar bg-primary" role="progressbar" :style="'width: '+(counts.enrolled.length/counts.scholars)*100+'%'" :aria-valuenow="(counts.enrolled.length/counts.scholars)*100"
                            aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <span class="text-muted fs-12 d-block text-truncate"><b>{{counts.enrolled.length}}</b> out of <b>{{counts.scholars}}</b> ongoing scholars are enrolled.</span>
                    </div>
                </div>

                <hr class="text-muted"/>

                <h6 class="fs-11 text-muted text-uppercase mb-3">Schools Semester</h6>
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="ri-hotel-line text-primary fs-17"></i>
                    </div>
                    <div class="flex-grow-1 ms-3 overflow-hidden">
                        <div class="progress mb-2 progress-sm">
                            <div class="progress-bar bg-primary" role="progressbar" :style="'width: '+(counts.semesters.length/counts.schools)*100+'%'" :aria-valuenow="(counts.semesters.length/counts.schools)*100"
                            aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <span class="text-muted fs-12 d-block text-truncate"><b>{{counts.semesters.length}}</b> out of <b>{{counts.schools}}</b> schools with active semester.</span>
                    </div>
                </div>

                <hr class="text-muted"/>
                <div class="mt-2">
                    <ul class="list-unstyled vstack gap-3">
                        <li>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-xs">
                                        <div class="avatar-title rounded bg-soft-secondary text-secondary">
                                        <i class="ri-file-text-line fs-17"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-0 fs-13">Lacking Grades</h5>
                                    <p class="mb-0 fs-12 text-muted">2348 files</p>
                                </div>
                                <div class="avatar-group"><div class="avatar-group-item"><a class="d-inline-block" href="javascript: void(0);" target="_self" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Stine Nielsen"><img src="/img/avatar-1.7e66bd07.jpg" alt="" class="rounded-circle avatar-xxs"></a></div><div class="avatar-group-item"><a class="d-inline-block" href="javascript: void(0);" target="_self" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Stine Nielsen"><img src="/img/avatar-2.0c4e66a9.jpg" alt="" class="rounded-circle avatar-xxs"></a></div><div class="avatar-group-item"><a class="d-inline-block" href="javascript: void(0);" target="_self" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Stine Nielsen"><img src="/img/avatar-3.910af76d.jpg" alt="" class="rounded-circle avatar-xxs"></a></div><div class="avatar-group-item"><a class="" href="javascript: void(0);" target="_self"><div class="avatar-xxs"><span class="avatar-title rounded-circle bg-info text-white">5</span></div></a></div></div>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-xs">
                                        <div class="avatar-title rounded bg-soft-success text-success">
                                        <i class="ri-gallery-line fs-17"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-0 fs-13">Unreleased Benefits</h5>
                                    <p class="mb-0 fs-12 text-muted">12480 files</p>
                                </div>
                                <div class="avatar-group"><div class="avatar-group-item"><a class="d-inline-block" href="javascript: void(0);" target="_self" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Stine Nielsen"><img src="/img/avatar-1.7e66bd07.jpg" alt="" class="rounded-circle avatar-xxs"></a></div><div class="avatar-group-item"><a class="d-inline-block" href="javascript: void(0);" target="_self" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Stine Nielsen"><img src="/img/avatar-2.0c4e66a9.jpg" alt="" class="rounded-circle avatar-xxs"></a></div><div class="avatar-group-item"><a class="d-inline-block" href="javascript: void(0);" target="_self" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Stine Nielsen"><img src="/img/avatar-3.910af76d.jpg" alt="" class="rounded-circle avatar-xxs"></a></div><div class="avatar-group-item"><a class="" href="javascript: void(0);" target="_self"><div class="avatar-xxs"><span class="avatar-title rounded-circle bg-info text-white">5</span></div></a></div></div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
         </div>
        <div class="file-manager-content w-100 p-3 pb-0" style="height: calc(100vh - 180px)" ref="box">
            <!-- <h5 class="fs-14 pt-2 pb-2 mb-2">{{current}}</h5>-->

            <div class="row mb-n3">
                <div class="col-md-3" v-for="(s,i) in counts.statuses" v-bind:key="s.id">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-light text-primary rounded-circle fs-3">
                                        <i :class="icons[i]" class="align-middle"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-uppercase fw-semibold fs-12 text-muted mb-1">{{s.name}}</p>
                                    <h4 class="mb-0"><span class="counter-value">{{s.status_count}}</span></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="input-group mb-2 mt-2">
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
                </div>
            </div>
            <div>
                
                <!-- <ul class="nav nav-tabs nav-tabs-custom nav-primary mb-3 nav-justified fs-12" role="tablist">
                    <li class="nav-item">
                        <b-link class="nav-link active All py-3" data-bs-toggle="tab" id="All" href="#home1"
                            role="tab" @click="changestatus('Ongoing Scholars')" aria-selected="true">Ongoing Scholars
                        </b-link>
                    </li>
                    <li class="nav-item" v-for="(s,i) in statuses" v-bind:key="s.id">
                        <b-link class="nav-link py-3" data-bs-toggle="tab" id="Pending"
                            href="#pending" role="tab" @click="changestatus(s.name)">
                            <i :class="icons[i]" class="me-1 align-bottom"></i> {{ s.name }} ({{ s.status_count }})
                        </b-link>
                    </li>
                </ul> -->
                <div data-simplebar style="height: calc(100vh - 380px)">
                    <div class="table-responsive">
                        <table class="table table-nowrap align-middle">
                            <thead class="text-muted table-light fs-11">
                                <tr>
                                    <th>Scholar</th>
                                    <th class="text-center">Program</th>
                                    <th class="text-center">Course</th>
                                    <th class="text-center">Level</th>
                                    <th class="text-end"></th>
                                </tr>
                            </thead>
                            
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="file-manager-sidebar">
            <div class="p-4 d-flex flex-column h-100">
               
                        
            </div>
         </div> -->
    </div>
</template>
<script>
import PageHeader from "@/Shared/Components/PageHeader.vue";
export default {
    components: { PageHeader },
    props: ['programs'],
    data() {
        return {
            currentUrl: window.location.origin,
            title: "Monitoring",
            items: [{text: "View", href: "/",},{text: "Dasboard",active: true,},],
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
            current: 'Ongoing Scholars',
            counts: { semesters: [], enrolled: []},
            icons: ['ri-checkbox-circle-fill text-success','ri-question-line text-warning','ri-close-circle-fill text-danger','ri-error-warning-fill text-info']
        };
    },
    created(){
        this.fetch();
        this.fetchStatus();
    },
    methods: {
        fetchStatus(){
            axios.get(this.currentUrl+'/monitoring', {
                params: {
                    search: 'counts'
                }
            })
            .then(response => {
                this.counts = response.data;
                console.log(this.statuses);
            })
            .catch(err => console.log(err));
        },
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

            page_url = page_url || '/monitoring';
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
        changestatus(title){
            this.current = title;
        }
    }
}
</script>
<style>
    .file-manager-sidebar {
        min-width: 400px;
        max-width: 400px;
        height: calc(100vh - 180px);
    }

</style>