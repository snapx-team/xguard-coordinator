<template>
    <div v-if="supervisorsData !== null">
        <div class="bg-gray-100 w-full h-64 absolute top-0 rounded-b-lg" style="z-index: -1"></div>

        <div class="flex flex-wrap p-4 pl-10">
            <h3 class="text-3xl text-gray-800 font-bold py-1 pr-8">Dashboard</h3>
        </div>

        <div class="mx-10 my-3 space-y-5 shadow-xl p-5 bg-white">

            <!-- date filter -->
            <div class="flex flex-wrap bg-gray-50 p-4 rounded items-end">
                <div class="flex-column w-80 mr-4 ">
                    <p class="block text-xs font-bold leading-4 tracking-wide uppercase text-gray-600 pb-1">Select
                        Date</p>
                    <date-picker type="date" v-model="selectedDateRange"
                                 placeholder="YYYY-MM-DD"
                                 :default-value="new Date()"
                                 format="YYYY-MM-DD"
                                 :shortcuts="shortcuts"
                                 @change="updateDateRange"
                                 range
                    ></date-picker>
                </div>
                <h1 class="text-gray-700 font-semibold text-xl pl-2 border-l mb-1 mt-3">
                    Between <span
                    class="text-purple-900 font-bold">{{ selectedDateRange[0] | moment("MMM DD, YYYY") }}</span>
                    and <span
                    class="text-purple-900 font-bold">{{ selectedDateRange[1] | moment("MMM DD, YYYY") }}</span>
                </h1>
            </div>

            <div class="flex flex-wrap bg-gray-50 p-4 rounded items-end">
                <div class="flex-column w-40 ">
                    <p class="block text-xs font-bold leading-4 tracking-wide text-gray-600 pb-1">DISTANCE <span
                        class="text-gray-400">meters</span></p>
                    <counter v-model="distanceThreshold" :max="1000" min="1"
                             :disabled="isLoadingLocationPathData"></counter>
                </div>

                <div class="flex-column w-40 mr-4">
                    <p class="block text-xs font-bold leading-4 tracking-wide text-gray-600 pb-1">STOP TIME <span
                        class="text-gray-400">minutes</span></p>
                    <counter v-model="timeThreshold" :max="240" min="1" :disabled="isLoadingLocationPathData"></counter>
                </div>

                <h1 class="text-gray-700 font-semibold text-xl pl-2 border-l mb-1 mt-3">
                    Calculating stops within <span
                    class="text-purple-900 font-bold">{{ distanceThreshold }}m radius</span>
                    for over <span class="text-purple-900 font-bold">{{ timeThreshold }} minutes</span>
                </h1>
            </div>

            <hr class="mb-5"/>

            <div class="flex items-center" v-if="mapPaneData.pathData.path.length > 0">
                <div class="w-40">
                    <p class="font-bold text-gray-600">Time:
                        {{ mapPaneData.pathData.path[selectedPathPing].createdAt | moment("HH:mm") }}</p>
                    <p class="font-semibold text-xs text-gray-400">Ping: {{ selectedPathPing }}</p>
                </div>
                <input v-model="selectedPathPing" id="range" type="range" name="positionSlider" :min="1"
                       :max="mapPaneData.pathData.path.length -1" class="w-full h-2 bg-blue-100 appearance-none"/>
            </div>

            <div>
                <div>
                    <button
                        v-if="!supervisorPaneIsVisible"
                        @click="toggleSupervisorPane"
                        class="py-4 font-semibold text-indigo-600 hover:text-indigo-800 transition duration-300 ease-in-out focus:outline-none">
                        Show Supervisors
                        <i class="fa fa-th-large ml-2"></i>
                    </button>
                </div>
                <div class="h-full" :key="componentKey">
                    <splitpanes class="default-theme">
                        <pane :size="20" min-size="15" :max-size="35" v-if="supervisorPaneIsVisible"
                              class="bg-indigo-50">
                            <SupervisorPane :supervisors-data="supervisorsData"/>
                        </pane>
                        <pane :size="50" class="flex-grow">
                            <MapPane :distance-threshold="distanceThreshold"
                                     :is-loading-location-path-data="isLoadingLocationPathData"
                                     :map-pane-data="mapPaneData"
                                     :selected-path-ping="selectedPathPing"/>
                        </pane>
                        <pane :size="20" min-size="15" :max-size="35" v-if="dataPaneIsVisible"
                              class="bg-indigo-50 h-max">
                            <DataPane :map-pane-data="mapPaneData"
                                      :supervisor="selectedSupervisor"
                                      :supervisorShift="selectedSupervisorShift"/>
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
import DataPane from "./dashboardComponents/DataPane";
import SupervisorPane from "./dashboardComponents/SupervisorPane";
import moment from "moment";
import Counter from "../global/Counter";
import _ from "lodash"
import MapPane from "./dashboardComponents/MapPane";

export default {

    inject: ["eventHub"],

    components: {
        MapPane,
        Counter,
        SupervisorPane,
        DataPane,
        Splitpanes,
        Pane
    },
    mixins: [axiosCalls],

    mounted() {
        this.getSupervisorsData();
    },

    watch: {
        distanceThreshold: _.debounce(function () {
            this.reloadLocationPathData()
        }, 400),

        timeThreshold: _.debounce(function () {
            this.reloadLocationPathData()
        }, 400),
    },

    data() {
        return {
            selectedPathPing: 0,
            distanceThreshold: 50,
            timeThreshold: 5,
            isLoadingLocationPathData: false,
            componentKey: 0,
            supervisorsData: null,
            selectedDateRange: [moment().startOf('week').toDate(), new Date()],
            selectedSupervisorShift: null,
            selectedSupervisor: {},
            mapPaneData: {
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
                pathData: {
                    isLoadingPath: false,
                    stops: [],
                    stopsMarkers: [],
                    path: [],
                    totalDistance: 0,
                },
            },
            dataPaneIsVisible: false,
            supervisorPaneIsVisible: true,
            shortcuts: [
                {text: 'Today', onClick: () => [new Date(), new Date()]},
                {
                    text: 'Yesterday',
                    onClick: () => [moment().subtract(1, 'day').toDate(), new Date()]
                },
                {
                    text: 'Start of Week',
                    onClick: () => [moment().startOf('week').toDate(), new Date()]
                },
                {
                    text: 'Start of Month',
                    onClick: () => [moment().startOf('month').toDate(), new Date()]
                },
                {
                    text: 'Last Week',
                    onClick: () => [moment().subtract(1, 'week').toDate(), new Date()]
                },
                {
                    text: 'Last Month',
                    onClick: () => [moment().subtract(1, 'month').toDate(), new Date()]
                }
            ],
        };
    },

    created() {

        this.eventHub.$on("show-data-pane-info", (supervisorShiftData) => {
            this.showDataPaneInfo(supervisorShiftData);
        });
        this.eventHub.$on("toggle-supervisor-pane", () => {
            this.toggleSupervisorPane();
        });
        this.eventHub.$on("toggle-data-pane", () => {
            this.toggleDataPane();
        });
        this.eventHub.$on("load-path-data", () => {
            this.loadLocationPathData();
        });
    },

    beforeDestroy() {
        this.eventHub.$off('show-data-pane-info');
        this.eventHub.$off('toggle-supervisor-pane');
        this.eventHub.$off('toggle-data-pane');
        this.eventHub.$off('load-path-data');
    },

    methods: {

        updateDateRange() {
            this.getSupervisorsData();
            this.componentKey++
        },

        reloadLocationPathData() {
            if (this.selectedSupervisorShift) {
                this.mapPaneData.window_open = false
                this.loadLocationPathData()
            }
        },

        loadLocationPathData() {
            this.isLoadingLocationPathData = true
            this.asyncGetLocationPathData(this.selectedSupervisorShift.id, this.distanceThreshold, this.timeThreshold).then((data) => {
                if(data.data !== ''){
                    this.mapPaneData.pathData.path = data.data.path;
                    this.mapPaneData.pathData.stops = data.data.stops;
                    this.mapPaneData.pathData.totalDistance = data.data.totalDistance;
                    this.setStopsMarkers();
                }
                this.isLoadingLocationPathData = false
            });
        },

        toggleSupervisorPane() {
            this.supervisorPaneIsVisible = !this.supervisorPaneIsVisible
        },

        toggleDataPane() {
            this.dataPaneIsVisible = !this.dataPaneIsVisible
        },

        getSupervisorsData() {
            this.eventHub.$emit("set-loading-state", true);
            this.asyncGetSupervisorsData(this.selectedDateRange).then((data) => {
                this.supervisorsData = data.data.supervisorsData;
                this.eventHub.$emit("set-loading-state", false);
            });
        },

        showDataPaneInfo(supervisorShiftData) {
            this.dataPaneIsVisible = true;
            this.selectedSupervisorShift = supervisorShiftData.supervisorShift;
            this.selectedSupervisor = supervisorShiftData.supervisor;
            this.setJobSiteMarkers();
            this.loadLocationPathData();
            this.mapPaneData.googleMapRefreshKey++;
        },

        setJobSiteMarkers() {
            this.mapPaneData.jobSiteMarkers = this.selectedSupervisorShift.jobSiteVisits.map(e => ({
                label: {
                    text: (e.jobSite.contracts[0].id).toString(),
                    color: "#fff",
                    fontWeight: "bold",
                    fontSize: "14px",
                },
                position: {lat: parseFloat(e.address.lat), lng: parseFloat(e.address.lng)},
                name: e.jobSite.contracts[0].name,
                address: e.address.name,
                type: 'jobSiteVisit'
            }));
        },

        setStopsMarkers() {
            this.mapPaneData.pathData.stopsMarkers = this.mapPaneData.pathData.stops.map((e, index) => ({
                label: {
                    text: (index + 1).toString(),
                },
                icon: {
                    url: 'https://www.google.com/mapfiles/dd-end.png'
                },
                position: {lat: parseFloat(e.averageLat), lng: parseFloat(e.averageLng)},
                totalTime: e.totalTime,
                startTime: e.startTime,
                endTime: e.endTime,
                coordinates: e.averageLat + ', ' + e.averageLng,
                type: 'stop'
            }));
        },
    },
};
</script>

<style scoped>

.splitpanes__pane {
    height: auto;
}

.list-inline-item {
    cursor: pointer;
}

</style>
