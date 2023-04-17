<template>
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Scholarship Program Awarded Through the Years</h4>
            <div>
                <button class="btn btn-soft-primary btn-sm" type="button">
                    <div class="btn-content"> View all </div>
                </button>
            </div>
        </div>
        <div class="card-body">
            <apexchart ref="realtimeChart" class="apex-charts mb-n5 mt-2" type="bar" dir="ltr" height="400" :series="series"
                :options="chartOptions">
            </apexchart>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            currentUrl: window.location.origin,
            years: [],
            provinces: [],
            province: null,
            is_undergrad: null,
            series: [],
            chartOptions: {
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

    created() {
        this.fetch();
    },

    methods: {
        fetch() {
            axios.get(this.currentUrl + '/insights',{
                params : {
                    search : 'years',
                    province : this.province,
                    is_undergrad : this.is_undergrad
                }
            })
            .then(response => {
                this.provinces = response.data.provinces;
                this.chartOptions = {
                    ...this.chartOptions,
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
                            categories: response.data.categories,
                            tickPlacement: 'on'
                        }
                    }
                };
                this.series = response.data.lists;
            })
            .catch(err => console.log(err));
        }
    }
}
</script>