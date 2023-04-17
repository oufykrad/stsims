<template>
    <Head title="Insights" />
    <PageHeader :title="title" :items="items" />

    <b-row>
        <b-col xxl="4">

        </b-col>
        <b-col xxl="8" order-xxl="0" class="order-first">
            <b-row>
                <b-col lg="4" md="6" v-for="(item, index) of firsts" :key="index">
                    <b-card no-body>
                        <b-card-body>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-light text-primary rounded-circle fs-3">
                                        <i :class="`bx ${item.icon} align-middle`"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-uppercase fw-semibold fs-12 text-muted mb-1">
                                        {{ item.name }}
                                    </p>
                                    <h4 class="mb-0">
                                        <span class="counter-value">
                                        {{ item.total }}
                                        </span>
                                    </h4>
                                </div>
                                <div class="flex-shrink-0 align-self-end">
                                    <apexchart class="apex-charts" height="40" width="100" type="area" dir="ltr" :series="item.series" :options="chartOptions"></apexchart>
                                </div>
                            </div>
                        </b-card-body>
                    </b-card>
                </b-col>
            </b-row>

            <b-row>
                <b-col xl="12">
                    <Location :locations="locations" :total="total"/>
                </b-col>
            </b-row>
        </b-col>
        <b-col xxl="12">
            <Program />
        </b-col>
        <b-col xxl="4">
            <School :schools="schools" :total="total"/>
        </b-col>
         <b-col xxl="4">
            <Course :courses="courses" :total="total "/>
        </b-col>
         <b-col xxl="4">
            <Gender :gender="gender"/>
        </b-col>
    </b-row>
</template>
<script>
import _ from 'lodash';
import Location from './Parts/Location.vue';
import Program from './Parts/Program.vue';
import School from './Parts/School.vue';
import Course from './Parts/C.vue';
import Gender from './Parts/Gender.vue';
import PageHeader from "@/Shared/Components/PageHeader.vue";
export default {
    components: { PageHeader, Program, School, Course, Gender, Location },
    data() {
        return {
            currentUrl: window.location.origin,
            title: "Insights",
            items: [{text: "Insights",href: "/",}, {text: "Scholar",active: true, }, ],
            firsts: [],
            schools: [],
            courses: [],
            locations: [],
            gender: {},
            total: '',
            chartOptions: {
                chart: {
                    type: 'area',
                    height: 40,
                    sparkline: {
                        enabled: true
                    }
                },
                stroke: {
                    curve: 'smooth',
                    width: 2,
                },
                dataLabels: {
                    enabled: false
                },
                colors: ['#f1b44c'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        inverseColors: false,
                        opacityFrom: 0.45,
                        opacityTo: 0.05,
                        stops: [25, 100, 100, 100]
                    },
                },
                tooltip: {
                    fixed: {
                        enabled: false
                    },
                    x: {
                        show: true
                    },
                    marker: {
                        show: false
                    }
                }
            }
        };
    },
    created(){
        this.fetch();
    },
    methods: {
        fetch() {
            axios.get(this.currentUrl + '/insights', {
                params: {
                    search: 'lists'
                }
            })
            .then(response => {
                this.firsts = response.data.first;
                this.schools = response.data.schools;
                this.locations = response.data.locations;
                this.courses = response.data.courses;
                this.gender = response.data.gender;
                this.total = response.data.total;
            })
            .catch(err => console.log(err));
        }
    }
}
</script>

