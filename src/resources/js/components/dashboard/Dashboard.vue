<template>
    <div v-if="supervisorsData !== null">
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
                                <div class="py-3 overflow-auto" style="height:550px;">
                                    <user-card
                                        v-for="supervisor in supervisorsData"
                                        :key="supervisor.id"
                                        :supervisor="supervisor"
                                        @click.native="showDataPaneInfo(supervisor.supervisorShifts)"
                                    ></user-card>
                                </div>
                            </div>
                        </pane>
                        <pane :size="50" class="flex-grow">
                            <div>

                                <gmap-map id="map" v-bind="options" :key="googleMapRefreshKey">

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
                                        v-for="(jobSiteMarker, index) in jobSiteMarkers"
                                        :key="index"
                                        :jobSiteMarker="jobSiteMarker"
                                        @click.native="openWindow(jobSiteMarker, index)"
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
        this.getSupervisorsData();
    },

    data() {
        return {
            googleMapRefreshKey: 0,
            allJobSiteVisits: null,
            supervisorsData: null,
            jobSiteMarkers: [],
            selectedDate: new Date(),
            showSupervisorPane: true,
            showDataPane: false,
            options: {
                zoom: 11,
                center: {
                    lat: 45.533550,
                    lng: -73.602119,
                },
            },
            infoPosition: null,
            infoOptions: {
                pixelOffset: {
                    width: 0,
                    height: -35,
                },
                maxWidth: 200,
                content: null,
            },
            window_open: false,
            currentMarkerIndex: null,
        };
    },

    computed: {
        google: gmapApi,
    },

    methods: {
        getSupervisorsData() {
            this.eventHub.$emit("set-loading-state", true);
            this.asyncGetSupervisorsData().then((data) => {
                this.supervisorsData = data.data.supervisorsData;
                this.eventHub.$emit("set-loading-state", false);
            });
        },

        showDataPaneInfo(supervisorShifts) {
            this.showDataPane = true;
            this.allJobSiteVisits = supervisorShifts.flatMap((e) => e.jobSiteVisits);
            this.setJobSiteMarkers();
            this.googleMapRefreshKey++;
        },

        setJobSiteMarkers() {
            this.jobSiteMarkers =  this.allJobSiteVisits.map( e => ({
                label: {
                    text: (e.jobSite.contracts[0].id).toString(),
                    color: "#fff",
                    fontWeight: "bold",
                    fontSize: "14px",
                },
                position: { lat: parseFloat(e.jobSite.lat), lng: parseFloat(e.jobSite.lng) },
                name: e.jobSite.contracts[0].name,
                address: e.jobSite.address
            }));
        },

        getPosition: function (marker) {
            return {
                lat: marker.position.lat,
                lng: marker.position.lng
            };
        },
        openWindow(marker, index) {

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

            if (this.currentMarkerIndex === index) {
                this.window_open = !this.window_open;
            } else {
                this.window_open = true;
                this.currentMarkerIndex = index;
            }
        },
    },
};
</script>

<style scoped>
#map {
    height: 600px;
    width: 100%;
    margin: 0 auto;
}

.list-inline-item {
    cursor: pointer;
}
</style>
