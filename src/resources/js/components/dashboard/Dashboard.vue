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
                        v-if="!leftPaneData.showSupervisorPane"
                        @click="leftPaneData.showSupervisorPane = !leftPaneData.showSupervisorPane"
                        class="py-4 font-semibold text-indigo-600 hover:text-indigo-800 transition duration-300 ease-in-out focus:outline-none">
                        Show Supervisors
                        <i class="fa fa-th-large ml-2"></i>
                    </button>
                </div>
                <div class="h-full">
                    <splitpanes class="default-theme">
                        <pane :size="20" min-size="15" :max-size="35" v-if="leftPaneData.showSupervisorPane"
                              class="bg-indigo-50">
                            <div class="flex flex-wrap flex-col">
                                <div class="flex justify-between p-2 bg-indigo-800 border-b">

                                    <div class="flex items-center">
                                        <button class="bg-indigo-700 hover:bg-indigo-500 transition duration-150 ease-in-out rounded px-2 py-1 mr-2" v-if="leftPaneData.panelName === leftPaneData.panelNames.shifts" @click="previousPanel()">
                                            <i class="fas fa-arrow-left text-white"></i>
                                        </button>
                                        <h1 class="text-white">{{ leftPaneData.panelName }}</h1>

                                    </div>
                                    <div>
                                        <button @click="leftPaneData.showSupervisorPane = !leftPaneData.showSupervisorPane"
                                                class="focus:outline-none flex flex-col items-center text-gray-400 hover:text-gray-500 transition duration-150 ease-in-out pl-8"
                                                type="button">
                                            <i class="fas fa-times"></i>
                                            <span
                                                class="text-xs font-semibold text-center leading-3 uppercase p-1">Esc</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="py-3 overflow-auto" style="height:550px;">

                                    <transition mode="out-in"
                                                :name="this.leftPaneData.transitionName">

                                        <div class="block" v-if="leftPaneData.panelName === leftPaneData.panelNames.supervisors"
                                             key="1">
                                            <user-card
                                                v-for="supervisor in supervisorsData"
                                                :key="supervisor.id"
                                                :supervisor="supervisor"
                                                @click.native="showSupervisorShifts(supervisor.supervisorShifts)"
                                            ></user-card>
                                        </div>

                                        <div class="block"
                                             v-if="leftPaneData.panelName === leftPaneData.panelNames.shifts" key="2">
                                            <shiftCard
                                                v-for="(shift, index) in selectedSupervisorShifts"
                                                :shift = shift
                                                :key="index"
                                                @click.native="showDataPaneInfo(shift)">
                                                {{ shift.startTime }}
                                            </shiftCard>
                                        </div>
                                    </transition>
                                </div>
                            </div>
                        </pane>
                        <pane :size="50" class="flex-grow">
                            <div>
                                <gmap-map id="map" v-bind="mapPaneData.options" :key="mapPaneData.googleMapRefreshKey">

                                    <gmap-marker
                                        :key="index"
                                        v-for="(m, index) in mapPaneData.jobSiteMarkers"
                                        :position="m.position"
                                        :icon="m.icon"
                                        :clickable="true"
                                        :label="m.label"
                                        @click="openWindow(m, index)"
                                    />

                                    <gmap-info-window
                                        @closeclick="mapPaneData.window_open=false"
                                        :opened="mapPaneData.window_open"
                                        :position="mapPaneData.infoPosition"
                                        :options="mapPaneData.infoOptions"
                                    >

                                    </gmap-info-window>
                                </gmap-map>
                            </div>
                        </pane>
                        <pane :size="20" min-size="15" :max-size="35" v-if="rightPaneData.showDataPane"
                              class="bg-indigo-50 h-max">
                            <div class="flex flex-wrap flex-col">
                                <div class="flex justify-between p-2 bg-indigo-800 border-b">
                                    <h1 class="text-white">Data</h1>
                                    <div>
                                        <button @click="rightPaneData.showDataPane = !rightPaneData.showDataPane"
                                                class="focus:outline-none flex flex-col items-center text-gray-400 hover:text-gray-500 transition duration-150 ease-in-out pl-8"
                                                type="button">
                                            <i class="fas fa-times"></i>
                                            <span
                                                class="text-xs font-semibold text-center leading-3 uppercase p-1">Esc</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="py-3 h-auto">

                                    <job-site-card
                                        v-for="(jobSiteMarker, index) in mapPaneData.jobSiteMarkers"
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
import ShiftCard from "./dashboardComponents/shiftCard";

export default {

    inject: ["eventHub"],

    components: {
        ShiftCard,
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
        this.leftPaneData.panelName = this.leftPaneData.panelNames.supervisors;
        this.leftPaneData.transitionName = this.leftPaneData.transitionNames.next;
    },

    data() {
        return {
            supervisorsData: null,
            selectedDate: new Date(),
            selectedSupervisorShifts: [],
            mapPaneData:{
                jobSiteMarkers: [],
                googleMapRefreshKey: 0,
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
            },
            rightPaneData:{
                showDataPane: false,
            },
            leftPaneData: {
                showSupervisorPane: true,
                transitionName: "",
                panelName: "",
                panelNames: {
                    supervisors: "Supervisors",
                    shifts: "Shifts"
                },
                transitionNames: {
                    next: "next",
                    previous: "previous"
                }
            }
        };
    },

    computed: {
        google: gmapApi,
    },

    methods: {
        nextPanel() {
            this.leftPaneData.transitionName = this.leftPaneData.transitionNames.next;
            this.leftPaneData.panelName = this.leftPaneData.panelNames.shifts;

        },
        previousPanel() {
            this.leftPaneData.transitionName = this.leftPaneData.transitionNames.previous;
            this.leftPaneData.panelName = this.leftPaneData.panelNames.supervisors;
        },

        getSupervisorsData() {
            this.eventHub.$emit("set-loading-state", true);
            this.asyncGetSupervisorsData().then((data) => {
                this.supervisorsData = data.data.supervisorsData;
                this.eventHub.$emit("set-loading-state", false);
            });
        },

        showSupervisorShifts(supervisorShifts) {
            if(supervisorShifts.length > 0){
                this.selectedSupervisorShifts = supervisorShifts;
                this.nextPanel();
            }
            else{
                this.triggerInfoToast('This user has no shifts in this time span');
            }
        },

        showDataPaneInfo(supervisorShift) {
            this.rightPaneData.showDataPane = true;
            this.selectedJobSiteVisits = supervisorShift.jobSiteVisits;
            this.setJobSiteMarkers();
            this.mapPaneData.googleMapRefreshKey++;
        },

        setJobSiteMarkers() {
            this.mapPaneData.jobSiteMarkers = this.selectedJobSiteVisits.map(e => ({
                label: {
                    text: (e.jobSite.contracts[0].id).toString(),
                    color: "#fff",
                    fontWeight: "bold",
                    fontSize: "14px",
                },
                position: {lat: parseFloat(e.address.lat), lng: parseFloat(e.address.lng)},
                name: e.jobSite.contracts[0].name,
                address: e.address.name
            }));
        },

        getPosition: function (marker) {
            return {
                lat: marker.position.lat,
                lng: marker.position.lng
            };
        },
        openWindow(marker, index) {

            this.rightPaneData.showDataPane = true;
            this.mapPaneData.infoPosition = this.getPosition(marker);
            this.mapPaneData.infoOptions.content =
                '<div class="">' +
                '<p class="font-semibold">' +
                marker.name +
                '</p>' +
                '<p class="">' +
                marker.address +
                '</p>' +
                '</div>';

            if (this.mapPaneData.currentMarkerIndex === index) {
                this.mapPaneData.window_open = !this.mapPaneData.window_open;
            } else {
                this.mapPaneData.window_open = true;
                this.mapPaneData.currentMarkerIndex = index;
            }
        },
    },
};
</script>

<style scoped>

.splitpanes__pane {
    height: auto;
}

#map {
    height: 600px;
    width: 100%;
    margin: 0 auto;
}

.list-inline-item {
    cursor: pointer;
}

.next-leave, .previous-leave {
    opacity: 1;
}

.next-leave-active, .previous-leave-active {
    transition: all .2s ease
}

.next-leave-to {
    opacity: 0;
    transform: translateX(-50px);
}

.next-enter {
    opacity: 0;
    transform: translateX(50px);
}

.next-enter-active, .previous-enter-active {
    transition: all .2s ease
}

.next-enter-to, .previous-enter-to {
    opacity: 1;
}

.previous-leave-to {
    opacity: 0;
    transform: translateX(50px);
}

.previous-enter {
    opacity: 0;
    transform: translateX(-50px);
}

</style>
