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
                        v-if="!supervisorPaneIsVisible"
                        @click="toggleSupervisorPane"
                        class="py-4 font-semibold text-indigo-600 hover:text-indigo-800 transition duration-300 ease-in-out focus:outline-none">
                        Show Supervisors
                        <i class="fa fa-th-large ml-2"></i>
                    </button>
                </div>
                <div class="h-full">
                    <splitpanes class="default-theme">
                        <pane :size="20" min-size="15" :max-size="35" v-if="supervisorPaneIsVisible"
                              class="bg-indigo-50">
                            <SupervisorPane :supervisors-data="supervisorsData"/>
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

export default {

    inject: ["eventHub"],

    components: {
        SupervisorPane,
        DataPane,
        Splitpanes,
        Pane
    },
    mixins: [axiosCalls],

    mounted() {
        this.getSupervisorsData();
    },

    data() {
        return {
            supervisorsData: null,
            selectedDate: new Date(),
            selectedSupervisorShift: {},
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
            },
            dataPaneIsVisible: false,
            supervisorPaneIsVisible: true,
        };
    },

    computed: {
        google: gmapApi,
    },

    created() {
        this.eventHub.$on("open-gmap-window", (data) => {
            this.openWindow(data.jobSiteMarker, data.index);
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
    },

    beforeDestroy() {
        this.eventHub.$off('open-gmap-window');
        this.eventHub.$off('show-data-pane-info');
        this.eventHub.$off('toggle-supervisor-pane');
        this.eventHub.$off('toggle-data-pane');
    },

    methods: {

        toggleSupervisorPane() {
            this.supervisorPaneIsVisible = !this.supervisorPaneIsVisible
        },

        toggleDataPane() {
            this.dataPaneIsVisible = !this.dataPaneIsVisible
        },

        getSupervisorsData() {
            this.eventHub.$emit("set-loading-state", true);
            this.asyncGetSupervisorsData().then((data) => {
                this.supervisorsData = data.data.supervisorsData;
                this.eventHub.$emit("set-loading-state", false);
            });
        },

        showDataPaneInfo(supervisorShiftData) {
            this.dataPaneIsVisible = true;
            this.selectedSupervisorShift = supervisorShiftData.supervisorShift;
            this.selectedSupervisor = supervisorShiftData.supervisor;
            this.setJobSiteMarkers();
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

            this.dataPaneIsVisible = true;
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

</style>
