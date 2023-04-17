<template>
    <div class="p-4 d-flex flex-column h-100">
        <div class="card">
            <div class="card-header p-0 border-0 bg-soft-light">
                <div class="row g-0">
                    <div class="col-sm-12">
                        <div class="p-3 border border-dashed border-start-0">
                            <h5 class="mb-1">
                                <span>{{total}}</span>
                                <span class="text-success ms-1 fs-12">schools</span>
                            </h5>
                            <p class="text-muted mb-0">Overall</p>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="p-3 border border-dashed border-start-0 border-end-0">
                            <h5 class="mb-1"><span>{{ count }}</span><span class="text-success ms-1 fs-12">schools</span></h5>
                            <p class="text-muted mb-0">{{agency.region.name}}, {{agency.region.region}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-auto">
            <h6 class="fs-11 text-muted text-uppercase mb-3">Storage Status</h6>
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <i class="ri-database-2-line fs-17"></i>
                </div>
                <div class="flex-grow-1 ms-3 overflow-hidden">
                    <div class="progress mb-2 progress-sm">
                        <div class="progress-bar bg-success" role="progressbar" :style="'width: '+(total/overall)*100+'%'" :aria-valuenow="(total/overall)*100"
                        aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <span class="text-muted fs-12 d-block text-truncate"><b>{{total}}</b> schools downloaded of <b>{{overall}}</b></span>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: ['agency', 'count', 'total', 'overall']
    }

</script>
