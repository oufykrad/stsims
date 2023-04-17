<template>
    <Head title="Financial Benefits" />
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

              
                <b-row>
                    <b-col xxl="12">
                        <hr class="mt-0"/>
                        
                        <b-card no-body class="card-height-100">
                            <b-card-body>
                                <div class="p-3 mt-n3 mx-n3 bg-soft-info rounded-top">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <h5 class="mb-0 fs-14"><a class="text-dark">{{ latest.month }}</a></h5>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="d-flex gap-1 align-items-center my-n2">
                                                <button class="btn btn-transparent btn-md btn avatar-xs p-0 favourite-btn active" type="button">
                                                    <div class="btn-content">
                                                        <span class="avatar-title bg-transparent fs-15">
                                                            <i class="ri-star-fill"></i>
                                                        </span>
                                                    </div>
                                                </button>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex mt-2 mb-n4 align-items-center">
                                    <div class="flex-grow-1">
                                        <div class="avatar-group">
                                            <b-link v-for="(list, index) of latest.scholars" :key="index" href="javascript: void(0);" class="avatar-group-item" v-b-tooltip.hover :title="list.name">
                                                <div class="avatar-xxs" v-if="list.avatar != 'avatar.jpg'">
                                                    <img :src="currentUrl+'/images/avatars/'+list.avatar" alt="" class="rounded-circle img-fluid" />
                                                </div>
                                                <div class="avatar-xxs" v-else>
                                                    <div class="avatar-title fs-16 rounded-circle bg-primary border-dashed border text-white">
                                                        {{ list.name[0] }}
                                                    </div>
                                                </div>
                                            </b-link>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div class="text-muted">
                                            <i class="ri-account-circle-fill  me-1 align-bottom"></i> {{latest.scholars.length}} Scholars
                                        </div>
                                    </div>
                                </div>
                            </b-card-body>
                            <b-card-footer class="bg-transparent border-top-dashed py-2">
                            <button @click="newRelease()" class="btn btn-light btn-sm w-100" type="button">
                                    <div class="btn-content"> Generate </div>
                                </button>
                            </b-card-footer>
                        </b-card>
                    </b-col>
                </b-row>

                
            </div>

            
        </div>
        <div class="file-manager-content w-100 p-4 pb-0" style="height: calc(100vh - 180px)" ref="box">
            <Index @status="set" :height="height" v-if="show == 'index'" ref="index"/>
            <Releasing  @status="back" :height="height" v-else-if="show == 'releasing'" ref="release"/>
        </div>
    </div>
</template>
<script>
import Index from './Pages/Index.vue';
import Releasing from './Pages/Releasing.vue';
import _ from 'lodash';
import { SimpleBar } from "simplebar-vue3";
import PageHeader from "@/Shared/Components/PageHeader.vue";
export default {
    props: ['dropdowns'],
    components: { PageHeader, SimpleBar, Index, Releasing },
    data() {
        return {
            currentUrl: window.location.origin,
            title: "Financial Benefits",
            items: [{text: "Lists",href: "/",}, {text: "Scholar",active: true, }, ],
            latest: {
                pending: [], scholars: []
            },
            show: 'index'
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
    created(){
        this.fetchLatest();
    },
    methods: {
        fetchLatest() {
            axios.get(this.currentUrl + '/financial-benefits/create', {
                params: {
                    type: 'lists'
                }
            })
            .then(response => {
                this.latest = response.data;
            })
            .catch(err => console.log(err));
        },
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
        type(data,selected){
            this.show = data;
            if(data == 'releasing'){
                this.$nextTick(function () {
                    this.$refs.release.fetch(selected);
                });
            }
            if(data == 'reimburse'){
                this.$nextTick(function () {
                    this.$refs.reimburse.fetch();
                });
            }
        },
        newRelease() {
            this.show = 'releasing';
            this.$nextTick(function () {
                this.$refs.release.fetch(this.latest);
            });
        },

        set(data){
            this.show = 'view';
            this.$nextTick(function () {
                this.$refs.view.set(data);
            });
        },

        back(){
            this.show = 'index';
            this.$nextTick(function () {
                this.$refs.index.fetch();
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

