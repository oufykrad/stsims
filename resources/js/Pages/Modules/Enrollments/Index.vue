<template>
    <Head title="Enrollments" />
    <PageHeader :title="title" :items="items" />
    <div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1">
        <div class="file-manager-sidebar">
            <div class="p-4 d-flex flex-column h-100">
                <form class="app-search d-none d-md-block" style="margin-top: -12px;">
                    <div class="position-relative">
                        <input type="text" class="form-control" placeholder="Search..." autocomplete="off" id="search-options" />
                        <span class="mdi mdi-magnify search-widget-icon"></span>
                        <span class="mdi mdi-close-circle search-widget-icon search-widget-icon-close d-none" id="search-close-options"></span>
                    </div>
                    <div class="dropdown-menu dropdown-menu-lg" id="search-dropdown">
                        <SimpleBar data-simplebar style="max-height: height: calc(100vh/2 - 326px)">
                            <div class="notification-list">
                                <b-link @click="choose(list)" v-for="(list, index) of names" :key="index" class="d-flex dropdown-item notify-item py-2">
                                    <img :src="currentUrl+'/images/avatars/'+list.profile.avatar" class="me-3 rounded-circle avatar-xs" alt="user-pic" />
                                    <div class="flex-1">
                                        <h6 class="m-0">{{ list.profile.name}}</h6>
                                        <span class="fs-11 mb-0 text-muted">{{list.program.name}}</span>
                                    </div>
                                </b-link>
                            </div>
                        </SimpleBar>
                    </div>
                </form>
                <ul class="list-unstyled mb-0 vstack gap-3" v-if="scholar">
                    <li>
                        <hr class="mt-0" />
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <img :src="currentUrl+'/images/avatars/'+scholar.profile.avatar" alt="" class="avatar-sm rounded">
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="fs-14 mb-1 text-primary">{{scholar.profile.name}}</h6>
                                <span :class="'badge bg-secondary bg-'+scholar.status.color">{{scholar.status.name}}</span>
                            </div>
                        </div>
                    </li>
                    <li><i class="mdi mdi-seal-variant me-2 align-middle text-primary fs-16"></i><span class="fs-12">{{scholar.program.name}}</span></li>
                    <li class="mt-n3"><i class="ri-building-line me-2 align-middle text-primary fs-16"></i><span class="fs-12">{{scholar.education.school.name}}</span></li>
                    <li class="mt-n3"><i class="mdi mdi-school-outline me-2 align-middle text-primary fs-16"></i><span class="fs-12">{{scholar.education.course.name}}</span></li>
                    <li v-if="scholar.education.school.semester">
                        <div v-if="!scholar.education.school.is_enrolled" class="alert alert-warning alert-dismissible alert-label-icon rounded-label" role="alert">
                            <i class="ri-alert-line label-icon"></i>Scholar is not Enrolled 
                            <button @click="createEnrollment()" class="btn btn-primary btn-sm float-end" style="margin-right: -40px; margin-top: -3px;" type="button"><div class="btn-content"> Enroll now</div></button>
                        </div>
                        <div v-else class="alert alert-secondary alert-dismissible alert-label-icon rounded-label" role="alert">
                            <i class="ri-check-double-line label-icon"></i>Scholar is currently enrolled 
                        </div>
                    </li>
                    <li v-else>
                        <div class="alert alert-danger alert-dismissible alert-label-icon rounded-label" v-b-tooltip.hover title="Please remind the coordinator" role="alert">
                            <i class="ri-error-warning-line label-icon"></i>Enrollment is still closed.
                        </div>
                    </li>
                    <li>
                        <div data-simplebar style="height: calc(100vh - 530px);" class="mt-n2">
                            <ul class="list-group mb-1">
                                <li class="list-group-item" v-for="list in scholar.enrollments" v-bind:key="list.id">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 avatar-xs">
                                                    <div class="avatar-title bg-soft-danger text-danger rounded">
                                                        <i class=" bx bxs-file-pdf "></i>
                                                    </div>
                                                </div>
                                                <div class="flex-shrink-0 ms-2" @click="fetchLists(list)" style="cursor: pointer;">
                                                    <h6 class="fs-13 mb-0"> {{ list.academic_year }}
                                                        <span class="text-muted"> / </span>
                                                        <span class="text-info">{{ list.level }}</span>
                                                        <span class="text-muted"> / </span>
                                                        <span class="text-warning">{{ list.semester }}</span></h6>
                                                    <small class="text-muted">{{ list.created_at}}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
                
            </div>
        </div>
        <div class="file-manager-content w-100 p-4 pb-0" style="height: calc(100vh - 180px)" ref="box">
            <Prospectus v-if="show == 'prospectus'" ref="prospectus"/>
            <Create v-else-if="show == 'create'" @status="message" :user="scholar" :dropdowns="dropdowns" ref="create" />
            <Lists v-else-if="show == 'lists'" @status="message" :lists="lists" :selected="selected" :user="scholar.code" ref="lists" />
            <Default :dropdowns="dropdowns" v-else />
        </div>
    </div>
    <Course ref="course"/>
</template>
<script>
import _ from 'lodash';
import Default from './Pages/Default.vue';
import Lists from './Pages/Lists.vue';
import Create from './Pages/Create.vue';
import Prospectus from './Pages/Prospectus.vue';
import Course from './Modals/Course.vue';
import { SimpleBar } from "simplebar-vue3";
import PageHeader from "@/Shared/Components/PageHeader.vue";
export default {
    props: ['dropdowns'],
    components: { PageHeader, SimpleBar, Course, Prospectus, Create, Lists, Default },
    data() {
        return {
            currentUrl: window.location.origin,
            title: "Enrollments",
            items: [{text: "Lists",href: "/",}, {text: "Scholar",active: true, }, ],
            names: [],
            keyword: '',
            scholar: null,
            show: 'default',
        };
    },
    watch: {
        datares: {
            deep: true,
            handler(val = null) {
                if(val != null && val !== ''){
                    this.message(val.data);
                    // console.log(val)
                }
            },
        },
    },
    computed: {
        datares() {
            return this.$page.props.flash.datares;
        },
    },
    mounted() {
        this.isCustomDropdown();
    },
    methods: {
        checkSearchStr: _.debounce(function (string) {
            this.keyword = string;
            this.search();
        }, 1000),
        search(){
            axios.get('/enrollments', {
                params: {
                    keyword: this.keyword,
                    search: true
                }
            })
            .then(response => {
                if (response) {
                    this.names = response.data.data;
                }
            })
            .catch(err => console.log(err));
        },
        fetchLists(list) {
            this.selected = list;
            axios.get(this.currentUrl + '/enrollments/' + list.id)
            .then(response => {
                this.lists = response.data.data;
                this.show = 'lists';
                 this.$nextTick(function () {
                this.$refs.lists.set(this.lists);
                 });
            })
            .catch(err => console.log(err));
        },
        choose(data){
            this.scholar = data;
            if (this.scholar.education.subcourse == null) {
                 this.$refs.course.set(
                    this.scholar.id, 
                    this.scholar.profile, 
                    this.scholar.education.school, 
                    this.scholar.education.course);
                    this.show = 'course';
            }else{
                this.viewProspectus();
            }
        },
        viewProspectus(){
            this.show = 'prospectus';
            this.$nextTick(function () {
                this.$refs.prospectus.set(this.scholar.education.information)
            });
        },
        createEnrollment(){
            this.show = 'create';
            this.$nextTick(function () {
                this.$refs.create.fetchSemesters(this.scholar.education.school.id, this.scholar.awarded_year,this.scholar.education.school.semester);
            });
        },
        isCustomDropdown() {
            var searchOptions = document.getElementById("search-close-options");
            var dropdown = document.getElementById("search-dropdown");
            var searchInput = document.getElementById("search-options");

            searchInput.addEventListener("focus", () => {
                var inputLength = searchInput.value.length;
                if (inputLength > 0) {
                    dropdown.classList.add("show");
                    searchOptions.classList.remove("d-none");
                } else {
                    dropdown.classList.remove("show");
                    searchOptions.classList.add("d-none");
                }
            });

            searchInput.addEventListener("keyup", () => {
                var inputLength = searchInput.value.length;
                if (inputLength > 0) {
                    dropdown.classList.add("show");
                    searchOptions.classList.remove("d-none");
                    this.checkSearchStr(searchInput.value);
                } else {
                    dropdown.classList.remove("show");
                    searchOptions.classList.add("d-none");
                }
            });

            searchOptions.addEventListener("click", () => {
                searchInput.value = "";
                dropdown.classList.remove("show");
                searchOptions.classList.add("d-none");
            });

            document.body.addEventListener("click", (e) => {
                if (e.target.getAttribute("id") !== "search-options") {
                    dropdown.classList.remove("show");
                    searchOptions.classList.add("d-none");
                }
            });
        },
        message(list) {
            let index = '';
            switch(this.show){
                case 'course': 
                    this.scholar = list;
                    this.viewProspectus();
                break;
                case 'create':
                    index = this.scholar.enrollments.findIndex(u => u.id === list.id);
                    if (index >= 0) {
                        this.scholar.enrollments[index] = list;
                        this.check();
                    }else{
                        this.scholar.enrollments.unshift(list);
                        this.check();
                        this.fetchLists(list);
                        this.scholar.education.school.is_enrolled = true;
                    }
                break;
                default:
                    this.selected = list;
                    index = this.scholar.enrollments.findIndex(u => u.id === list.id);
                    this.scholar.enrollments[index] = list;
            }
        },
        check() {
            if (this.scholar.enrollments.length > 0) {
                let count;
                count = this.scholar.enrollments.filter(x => x.is_locked === 0);
                this.count = count.length;
            }
        },
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

