<template>
    <b-modal v-model="showModal" hide-footer :title="province.name" class="v-modal-custom" modal-class="zoomIn" fullscreen>
        <div class="hstack gap-3 flex-wrap mt-n3">
            <div class="text-muted">
                <a class="text-primary fw-medium" href="#" target="_self">{{(province.region) ? province.region.name : ''}}</a>
            </div>
            <div class="vr"></div>
            <div class="text-muted">Region :
                <span class="text-body fw-medium">{{(province.region) ? province.region.region : ''}}</span>
            </div>
            <div class="vr"></div>
            <div class="text-muted">Total : 
                <span class="text-body fw-medium">{{province.scholars_count}} scholars</span>
            </div>
        </div>    
        <div class="row">
            <div class="col-md-12"><hr /></div>
            <div class="col-md-4">

            </div>

            <div class="col-md-8">
                <Count :firsts="firsts"/>
                <Table :districts="districts" :programs="programs"/>
            </div>

            <div class="col-md-12"><hr />   
                <Bar :options="chartOptions2" :series="series" />
            </div>
        </div>
    </b-modal>
</template>

<script>
import Bar from '../Components/Bar.vue';
import Table from '../Components/Table.vue';
import Count from '../Components/Count.vue';
export default {
    components : { Count, Table, Bar },
    data() {
        return {
            currentUrl: window.location.origin,
            showModal: false,
            code: '',
            programs: [],
            districts: [],
            province: {},
            firsts: [],
            series: [],
            chartOptions2: {
                chart: {
                    stacked: true,
                    toolbar: {
                        show: false,
                    },
                    zoom: {
                        enabled: true,
                    },
                    id: "vuechart-example",
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: "50%",
                        endingShape: "rounded",
                    },
                },
                dataLabels: {
                    enabled: false,
                },
                xaxis: {
                    labels: {
                        rotate: -45
                    },
                    categories: [
                        "Jan",
                        "Feb",
                        "Mar",
                        "Apr",
                        "May",
                        "Jun",
                        "Jul",
                        "Aug",
                        "Sep",
                        "Oct",
                        "Nov",
                        "Dec",
                    ],
                    tickPlacement: 'on'
                },
                yaxis: {
                    title: {
                        text: 'Scholars Count'
                    },
                },
                colors: ["#556ee6", "#ea6868", "#34c38f", "#f1b44c", "#a20cce", " #713d3d"],
                legend: {
                    position: "top",
                },
                fill: {
                    opacity: 1,
                },
            },
        }
    },
    methods: {
        set(list) {
            this.code = list;
            this.showModal = true;
            this.fetchStatus();
        },
        fetchStatus() {
            axios.get(this.currentUrl + '/insights'+'/'+this.code+'/edit')
            .then(response => {
                this.province = response.data.province;
                this.programs = response.data.programs;
                this.districts = response.data.districts;
                this.firsts = response.data.first;

                this.chartOptions2 = {
                    ...this.chartOptions2,
                    ...{
                        xaxis: {
                            labels: {
                                show: true,
                                rotate: -65,
                                rotateAlways: true,
                                minHeight: 100,
                                maxHeight: 180,
                                style: {
                                    colors: "red"
                                }
                            },
                            categories: response.data.categories.categories,
                            tickPlacement: 'on'
                        }
                    }
                };
                this.series = response.data.categories.lists;
            })
            .catch(err => console.log(err));
        },
    }
}
</script>
