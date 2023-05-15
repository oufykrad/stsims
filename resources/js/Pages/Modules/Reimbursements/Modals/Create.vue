<template>
    <b-modal v-model="showModal" title="Create Reimbursement" style="--vz-modal-width: 600px;" header-class="p-3 bg-light" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>    
        <b-form class="customform mb-2 mt-2">
            <div class="row">
                <div class="col-md-12 mb-3">
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
                    <ul class="list-unstyled mb-0 vstack gap-3" v-if="reimbursement.scholar">
                        <li>
                            <hr class="mt-0 text-muted"/>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <img :src="currentUrl+'/images/avatars/'+reimbursement.scholar.profile.avatar" alt="" class="avatar-sm rounded">
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="fs-14 mb-1 text-primary">{{reimbursement.scholar.profile.name}}</h6>
                                    <span :class="'badge bg-secondary bg-'+reimbursement.scholar.status.color">{{reimbursement.scholar.status.name}}</span>
                                </div>
                            </div>
                             <hr class="mt-3 text-muted"/>
                        </li>
                    </ul>
                </div>
                <div class="col-md-12 mb-3" v-if="reimbursement.scholar">
                    <div class="form-group">
                    <label>AY/Semester: <span v-if="form.errors" v-text="form.errors.school_semester_id" class="haveerror"></span></label>
                    <Multiselect
                        v-model="reimbursement.semester" 
                        id="ajax"  track-by="id"
                        placeholder="Search Semester" 
                        open-direction="bottom" 
                        :options="semesters"
                        :searchable="true" 
                        :allow-empty="false"
                        :custom-label="nameWithLang"
                        :show-labels="false"/>
                    </div>
                </div>
                <div class="col-md-12 mb-3" v-if="reimbursement.scholar">
                    <div class="form-group">
                        <label>Privilege: <span v-if="form.errors" v-text="form.errors.benefit_id" class="haveerror"></span></label>
                        <Multiselect 
                        v-model="reimbursement.privilege" 
                        :options="lists"
                        :allow-empty="true"
                        :show-labels="false"
                        label="name" track-by="id"
                        placeholder="Select Privilege"/>
                    </div>
                </div>
                <div class="col-md-12" v-if="reimbursement.scholar">
                    <div class="form-group">
                        <label>Amount: <span v-if="form.errors" v-text="form.errors.amount" class="haveerror"></span></label>
                        <input type="text" class="form-control" v-model="reimbursement.amount">
                    </div>
                </div>
            </div>
        </b-form>
        <template v-slot:footer>
            <b-button @click="showModal = false" variant="light" block>Cancel</b-button>
            <b-button @click="create('ok')" variant="primary"  block>Save</b-button>
        </template>
    </b-modal>
</template>
<script>
import { SimpleBar } from "simplebar-vue3";
import Multiselect from '@suadelabs/vue3-multiselect';
export default {
    props: ['privileges'],
    components : { Multiselect, SimpleBar },
    data() {
        return {
            currentUrl: window.location.origin,
            reimbursement: {
                privilege: '',
                semester: '',
                amount: '',
                scholar: ''
            },
            form: {},
            semesters: [],
            showModal: false,
            keyword: '',
            names: []
        }
    },
    computed: {
        lists : function() {
            return this.privileges.filter(x => x.is_reimburseable === 1);
        },
    },
    mounted() {
        this.isCustomDropdown();
    },
    methods: {
        set() {
            this.showModal = true;
        },
        nameWithLang ({ academic_year,semester }) {
            return `${academic_year} — ${semester.name}`
        },
        choose(data){
            this.reimbursement.scholar = data;
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
.multiselect, .multiselect__input, .multiselect__single {
  font-size: 13px;
}
.multiselect__placeholder {
  font-size: 13px;
}
.dropdown-menu-lg {
    width: 95%;
}
</style>