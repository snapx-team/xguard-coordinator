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
                            <div class="relative">
                                <transition enter-active-class="transition duration-500 ease-out transform"
                                            enter-class=" opacity-0 bg-blue-200"
                                            leave-active-class="transition duration-300 ease-in transform"
                                            leave-to-class="opacity-0 bg-blue-200">
                                    <div class="z-50 overflow-auto absolute inset-0 bg-gray-400 bg-opacity-50 flex"
                                         v-if="isLoadingLocationPathData">
                                        <loading-animation :size="70" class="m-auto"></loading-animation>
                                    </div>
                                </transition>

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

                                    <gmap-marker
                                        :key="index"
                                        v-for="(m, index) in mapPaneData.pathData.stopsMarkers"
                                        :position="m.position"
                                        :icon="m.icon"
                                        :clickable="true"
                                        @click="openWindow(m, index)"
                                    />

                                    <GmapCircle
                                        v-if="selectedStopMarker"
                                        :center="selectedStopMarker"
                                        :radius="distanceThreshold"
                                        :visible="true"
                                        :options="{fillColor:'blue',fillOpacity:0.1}"
                                    ></GmapCircle>

                                    <gmap-info-window
                                        @closeclick="mapPaneData.window_open=false"
                                        :opened="mapPaneData.window_open"
                                        :position="mapPaneData.infoPosition"
                                        :options="mapPaneData.infoOptions"
                                    >
                                    </gmap-info-window>

                                    <gmap-polyline v-bind:path.sync="mapPaneData.pathData.path"
                                                   v-bind:options="{ strokeColor:'#43f5ff'}"></gmap-polyline>

                                </gmap-map>
                            </div>
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
import {gmapApi} from "vue2-google-maps";
import DataPane from "./dashboardComponents/DataPane";
import SupervisorPane from "./dashboardComponents/SupervisorPane";
import moment from "moment";
import Counter from "../global/Counter";
import LoadingAnimation from "../global/LoadingAnimation";
import _ from "lodash"

export default {

    inject: ["eventHub"],

    components: {
        LoadingAnimation,
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
            distanceThreshold: 50,
            timeThreshold: 5,
            selectedStopMarker: null,
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

    computed: {
        google: gmapApi,
    },

    created() {
        this.eventHub.$on("open-gmap-window", (data) => {
            this.openWindow(data.marker, data.index);
        });
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
        this.eventHub.$off('open-gmap-window');
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
                this.selectedStopMarker = null;
                this.mapPaneData.window_open = false
                this.loadLocationPathData()
            }
        },

        loadLocationPathData() {
            this.isLoadingLocationPathData = true
            this.asyncGetLocationPathData(this.selectedSupervisorShift.id, this.distanceThreshold, this.timeThreshold).then((data) => {
                this.mapPaneData.pathData.path = data.data.path;
                this.mapPaneData.pathData.stops = data.data.stops;
                this.setStopsMarkers();
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

        getPosition: function (marker) {
            return {
                lat: marker.position.lat,
                lng: marker.position.lng
            };
        },

        openWindow(marker, index) {
            this.dataPaneIsVisible = true;
            this.mapPaneData.infoPosition = this.getPosition(marker);
            if (marker.type === 'jobSiteVisit') {
                this.mapPaneData.infoOptions.content =
                    '<div class="">' +
                    '<p class="font-semibold pb-2">' +
                    marker.name +
                    '</p>' +
                    '<p class="">' +
                    marker.address +
                    '</p>' +
                    '</div>';
            } else if (marker.type === 'stop') {
                this.selectedStopMarker = marker.position
                this.mapPaneData.infoOptions.content =
                    '<div class="">' +
                    '<p class="font-semibold pb-2">' +
                    'Stopped roughly ' + marker.totalTime + ' minutes' +
                    '</p>' +
                    '<p class="font-semibold pb-2">' +
                    'Between: ' + moment.utc(marker.startTime).format('HH:mm') + 'h - ' + moment.utc(marker.endTime).format('HH:mm') + 'h ' +
                    '</p>' +
                    '<p class="pb-2">' +
                    'Coordinates:' +
                    '</p>' +
                    '<p class="">' +
                    marker.coordinates +
                    '</p>' +
                    '</div>';
            }

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

</style>
