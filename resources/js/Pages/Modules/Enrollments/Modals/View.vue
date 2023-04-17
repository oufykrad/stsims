<template>
    <b-modal v-model="showModal" title="View Enrollment" size="lg" header-class="p-3 bg-light" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>    
        <b-form class="customform mb-2">
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3 mt-2">
                         <div class="col-md-12 border-bottom">
                            <div class="d-flex mb-3">
                                <div class="flex-shrink-0 me-3">
                                    <img :src="currentUrl+'/images/avatars/'+enrollment.avatar" alt="" class="avatar-sm rounded-circle img-thumbnail">
                                </div>
                                <div class="flex-grow-1 align-self-center">
                                    <div class="text-muted">
                                        <h5 class="mb-0 fs-14">{{ enrollment.scholar }}</h5>
                                        <p class="mb-0 fs-12">{{ enrollment.school }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <b-row class="mt-4">
                            <b-col lg="4">
                                <div class="p-2 border border-dashed rounded">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-2">
                                            <div class="avatar-title rounded bg-transparent text-success fs-24">
                                                <i class="ri-calendar-line"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="text-muted mb-1">Academic Year :</p>
                                            <h6 class="mb-0">{{ enrollment.academic_year }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </b-col>
                            <b-col lg="4">
                                <div class="p-2 border border-dashed rounded">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-2">
                                            <div class="avatar-title rounded bg-transparent text-success fs-24">
                                                <i class="ri-calendar-todo-line"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="text-muted mb-1">Semester :</p>
                                            <h6 class="mb-0">{{ enrollment.semester }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </b-col>
                            <b-col lg="4">
                                <div class="p-2 border border-dashed rounded">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-2">
                                            <div class="avatar-title rounded bg-transparent text-success fs-24">
                                                <i class="ri-archive-line"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="text-muted mb-1">Level :</p>
                                            <h6 class="mb-0">{{ enrollment.level }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </b-col>
                        </b-row>
                        <div class="row g-2 mt-2">
                            <div class="col-lg-12">
                                <div class="border rounded border-dashed p-2" v-if="(enrollment.attachment)">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar-sm">
                                                <div class="avatar-title bg-light text-secondary rounded fs-24">
                                                    <i class="ri-folder-zip-line"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 overflow-hidden">
                                            <h5 class="fs-13 mb-1">
                                                <a class="text-body text-truncate d-block" :href="currentUrl+'/storage/'+enrollment.attachment.enrollments.file" target="_blank">Enrollment Attachment</a>
                                            </h5>
                                            <div>2.2MB</div>
                                        </div>
                                        <div class="flex-shrink-0 ms-2">
                                            <div class="d-flex gap-1">
                                                <button type="button" class="btn btn-icon text-muted btn-sm fs-18">
                                                    <i class="ri-download-2-line"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
    export default {
        data() {
            return {
                currentUrl: window.location.origin,
                enrollment: '',
                showModal: false
            }
        },

        methods: {
            set(data) {
                this.enrollment = data;
                console.log(this.enrollment);
                this.showModal = true;
            }
        }
    }
</script>
