<template>
    <div v-if="dashboardData !== null">
        <div class="bg-gray-100 w-full h-64 absolute top-0 rounded-b-lg" style="z-index: -1"></div>

        <div class="flex flex-wrap p-4 pl-10">
            <h3 class="text-3xl text-gray-800 font-bold py-1 pr-8">Dashboard</h3>
        </div>

        <div class="mx-10 my-3 space-y-5 shadow-xl p-5 bg-white">

            <!-- date filter -->
            <div class="flex flex-wrap bg-gray-50 p-4 rounded">
                <div class="flex-column w-80 mr-2 ">
                    <p class="block text-xs font-bold leading-4 tracking-wide uppercase text-gray-600 pb-1">Select
                        Date</p>
                    <date-picker type="date" v-model="selectedDate"
                                 placeholder="YYYY-MM-DD"
                                 format="YYYY-MM-DD"
                    ></date-picker>
                </div>
            </div>

            <hr class="mb-5"/>

            <div>
                <div>
                    <button
                        v-if="!showSupervisorPane"
                        @click="showSupervisorPane = !showSupervisorPane"
                        class="py-4 font-semibold text-indigo-600 hover:text-indigo-800 transition duration-300 ease-in-out focus:outline-none">
                        Show Supervisors
                        <i class="fa fa-th-large ml-2"></i>
                    </button>
                </div>
                <div class="h-full">
                    <splitpanes class="default-theme">
                        <pane :size="20" min-size="15" :max-size="35" v-if="showSupervisorPane" class="bg-indigo-50">
                            <div class="flex flex-wrap flex-col">
                                <div class="flex justify-between p-2 bg-indigo-800 border-b">
                                    <h1 class="text-white">Supervisors</h1>
                                    <div>
                                        <button @click="showSupervisorPane = !showSupervisorPane"
                                                class="focus:outline-none flex flex-col items-center text-gray-400 hover:text-gray-500 transition duration-150 ease-in-out pl-8"
                                                type="button">
                                            <i class="fas fa-times"></i>
                                            <span
                                                class="text-xs font-semibold text-center leading-3 uppercase">Esc</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="py-3">
                                    <user-card
                                        v-for="user in users"
                                        :key="user.id"
                                        :user="user"
                                        @click.native="showDataPane = true"
                                    ></user-card>
                                </div>
                            </div>
                        </pane>
                        <pane :size="50" class="flex-grow">
                            <div>

                                <gmap-map id="map" v-bind="options">

                                    <gmap-marker
                                        :key="index"
                                        v-for="(m, index) in jobSiteMarkers"
                                        :position="m.position"
                                        :icon="m.icon"
                                        :clickable="true"
                                        :label="m.label"
                                        @click="openWindow(m, index)"
                                    />

                                    <gmap-info-window
                                        @closeclick="window_open=false"
                                        :opened="window_open"
                                        :position="infoPosition"
                                        :options="infoOptions"
                                    >

                                    </gmap-info-window>
                                </gmap-map>
                            </div>
                        </pane>
                        <pane :size="20" min-size="15" :max-size="35" v-if="showDataPane" class="bg-indigo-50">
                            <div class="flex flex-wrap flex-col">
                                <div class="flex justify-between p-2 bg-indigo-800 border-b">
                                    <h1 class="text-white">Data</h1>
                                    <div>
                                        <button @click="showDataPane = !showDataPane"
                                                class="focus:outline-none flex flex-col items-center text-gray-400 hover:text-gray-500 transition duration-150 ease-in-out pl-8"
                                                type="button">
                                            <i class="fas fa-times"></i>
                                            <span
                                                class="text-xs font-semibold text-center leading-3 uppercase">Esc</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="py-3">
                                    <job-site-card
                                        v-for="(m, index) in jobSiteMarkers"
                                        :key="index"
                                        @click.native="openWindow(m, index)"
                                    ></job-site-card>
                                </div>
                            </div>
                        </pane>
                    </splitpanes>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import {axiosCalls} from "../../mixins/axiosCallsMixin";
import {Pane, Splitpanes} from "splitpanes";
import "splitpanes/dist/splitpanes.css";
import vSelect from "vue-select";
import Avatar from "../global/Avatar.vue";
import UserCard from "./dashboardComponents/UserCard";
import {gmapApi} from "vue2-google-maps";
import JobSiteCard from "./dashboardComponents/JobSiteCard";

export default {

    inject: ["eventHub"],

    components: {
        Splitpanes,
        Pane,
        vSelect,
        Avatar,
        UserCard,
        JobSiteCard
    },
    mixins: [axiosCalls],

    mounted() {
        this.getDashboardData();
    },

    data() {
        return {
            dashboardData: null,
            selectedDate: new Date(),
            showSupervisorPane: true,
            showDataPane: false,
            options: {
                zoom: 12,
                center: {
                    lat: 39.9995601,
                    lng: -75.1395161,
                },
                mapTypeId: "roadmap",
            },
            info_marker: null,
            infowindow: {
                lat: 39.9995601,
                lng: -75.1395161,
            },
            infoPosition: null,
            infoContent: null,
            infoOptions: {
                pixelOffset: {
                    width: 0,
                    height: -35,
                },
                maxWidth: 200,
                content: null,
            },
            window_open: false,
            currentMidx: null,
            users: [
                {id: 1, name: 'siamak Samie', email: 'siamak.samie@gmail.com'},
                {id: 2, name: 'siamak A', email: 'siamak.a@gmail.com'},
                {id: 3, name: 'siamak B', email: 'siamak.b@gmail.com'},
                {id: 4, name: 'siamak C', email: 'siamak.c@gmail.com'},
            ],

            jobSites: [
                {
                    "name": "Location 1",
                    "address": "215 West Girard Avenue 19123",
                    "location": {
                        "lat": 39.9695601,
                        "lon": -75.1395161
                    },
                    "label": "1",
                },
                {
                    "name": "Location 2",
                    "address": "5360 Old York Road 19141",
                    "location": {
                        "lat": 40.034038,
                        "lon": -75.145223
                    },
                    "label": "2",
                },
                {
                    "name": "Location 3",
                    "address": "1350 W Girard Avenue 19123",
                    "location": {
                        "lat": 39.9713524,
                        "lon": -75.159036
                    },
                    "label": "3",
                }
            ]
        };
    },

    computed: {
        google: gmapApi,
        jobSiteMarkers() {
            return this.jobSites.map(({label, location: {lat, lon}, name,  address}) => ({
                label: {
                    text: label,
                    color: "#fff",
                    fontWeight: "bold",
                    fontSize: "16px",
                },
                position: {
                    lat,
                    lng: lon,
                },
                name,
                address
            }));
        },
    },

    methods: {
        getDashboardData() {
            this.eventHub.$emit("set-loading-state", true);
            this.asyncGetDashboardData().then((data) => {
                this.dashboardData = data.data;
                this.eventHub.$emit("set-loading-state", false);
            });
        },

        getPosition: function (marker) {
            return {
                lat: parseFloat(marker.position.lat),
                lng: parseFloat(marker.position.lng),
            };
        },
        openWindow(marker, idx) {
            this.showDataPane = true;
            this.infoPosition = this.getPosition(marker);
            this.infoOptions.content =
                '<div class="">' +
                '<p class="font-semibold">' +
                marker.name +
                '</p>' +
                '<p class="">' +
                marker.address +
                '</p>' +
                '</div>';

            if (this.currentMidx === idx) {
                this.window_open = !this.window_open;
            } else {
                this.window_open = true;
                this.currentMidx = idx;
            }
        },
    },
};
</script>


<style scoped>
#map {
    height: 500px;
    width: 100%;
    margin: 0 auto;
}

.list-inline-item {
    cursor: pointer;
}
</style>
