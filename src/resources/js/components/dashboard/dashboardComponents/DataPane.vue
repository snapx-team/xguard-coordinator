<template>
    <div class="flex flex-wrap flex-col">
        <div class="flex justify-between p-2 bg-indigo-800 border-b">
            <h1 class="text-white">Data</h1>
            <div>
                <button @click="togglePane"
                        class="focus:outline-none flex flex-col items-center text-gray-400 hover:text-gray-500 transition duration-150 ease-in-out pl-8"
                        type="button">
                    <i class="fas fa-times"></i>
                    <span
                        class="text-xs font-semibold text-center leading-3 uppercase p-1">Esc</span>
                </button>
            </div>
        </div>

        <div class="overflow-auto" style="height:550px;">

            <!-- Tabs -->
            <ul class="flex mb-0 list-none flex-wrap py-4 flex-row">
                <li class="m-1 flex-1 text-center">
                    <a class="text-xs font-bold uppercase px-5 py-3 rounded block leading-normal"
                       v-on:click="toggleTabs(1)"
                       v-bind:class="{'text-gray-600 bg-gray-200 hover:bg-gray-300 cursor-pointer ': openTab !== 1, 'text-white bg-gray-500': openTab === 1}">
                        <i class="fas fa-car-side text-base mr-1"></i>
                    </a>
                </li>
                <li class="m-1 flex-1 text-center">
                    <a class="text-xs font-bold uppercase px-5 py-3 rounded block leading-normal"
                       v-on:click="toggleTabs(2)"
                       v-bind:class="{'text-gray-600 bg-gray-200 hover:bg-gray-300 cursor-pointer ': openTab !== 2, 'text-white bg-gray-500': openTab === 2}">
                        <i class="fas fa-stop text-base mr-1"></i>
                    </a>
                </li>
                <li class="m-1 flex-1 text-center">
                    <a class="text-xs font-bold uppercase px-5 py-3 rounded block leading-normal"
                       v-on:click="toggleTabs(3)"
                       v-bind:class="{'text-gray-600 bg-gray-200 hover:bg-gray-300 cursor-pointer ': openTab !== 3, 'text-white bg-gray-500': openTab === 3}">
                        <i class="fas fa-tachometer-alt text-base mr-1"></i>
                    </a>
                </li>
            </ul>

            <div
                class="relative flex flex-col min-w-0 break-words w-full mb-6 ">
                <div class="tab-content tab-space">
                    <div v-bind:class="{'hidden': openTab !== 1, 'block': openTab === 1}">
                        <job-site-card
                            v-for="(jobSiteMarker, index) in mapPaneData.jobSiteMarkers"
                            :key="index"
                            :jobSiteMarker="jobSiteMarker"
                            @click.native="openGmapWindow(jobSiteMarker, index)"
                        ></job-site-card>
                        <h1 v-if="mapPaneData.jobSiteMarkers.length === 0"
                            class="text-lg tracking-wide text-indigo-900 font-bold text-center my-5">
                            no visits found
                        </h1>
                    </div>
                    <div v-bind:class="{'hidden': openTab !== 2, 'block': openTab === 2}">
                        <h1 class="text-lg tracking-wide text-indigo-900 font-bold text-center my-5">
                            no stops found (feature coming soon)
                        </h1>
                    </div>
                    <div v-bind:class="{'hidden': openTab !== 3, 'block': openTab === 3}">

                        <div class="shadow-md rounded-lg overflow-hidden m-2">
                            <h3 class="px-5 py-3 border-b-2 border-gray-200 bg-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Shift
                            </h3>
                            <table class="min-w-full leading-normal">
                                <tbody>
                                <tr>
                                    <td class="px-5 py-5 bg-white text-sm">
                                        <p class="text-gray-900 whitespace-no-wrap font-bold">Checkin</p>
                                    </td>
                                    <td class="px-5 py-5 bg-white text-sm">
                                        <p class="text-gray-900 whitespace-no-wrap">{{ supervisorShift.startTime | moment("HH:mm") }}h</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-5 py-5 bg-white text-sm">
                                        <p class="text-gray-900 whitespace-no-wrap font-bold">Checkout</p>
                                    </td>
                                    <td class="px-5 py-5 bg-white text-sm">
                                        <p class="text-gray-900 whitespace-no-wrap">{{ supervisorShift.endTime | moment("HH:mm") }}h</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-5 py-5 bg-white text-sm">
                                        <p class="text-gray-900 whitespace-no-wrap font-bold">Total</p>
                                    </td>
                                    <td class="px-5 py-5 bg-white text-sm">
                                        <p class="text-gray-900 whitespace-no-wrap">{{ supervisorShift.endTime | moment("from", supervisorShift.startTime, true) }}</p>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="shadow-md rounded-lg overflow-hidden m-2">
                            <h3 class="px-5 py-3 border-b-2 border-gray-200 bg-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Odometer
                            </h3>
                            <table class="min-w-full leading-normal">
                                <tbody>
                                <tr>
                                    <td class="px-5 py-5 bg-white text-sm">
                                        <p class="text-gray-900 whitespace-no-wrap font-bold">Start</p>
                                    </td>
                                    <td class="px-5 py-5 bg-white text-sm">
                                        <p class="text-gray-900 whitespace-no-wrap">{{ supervisorShift.odometer.startOdometer }}km</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-5 py-5 bg-white text-sm">
                                        <p class="text-gray-900 whitespace-no-wrap font-bold">End</p>
                                    </td>
                                    <td class="px-5 py-5 bg-white text-sm">
                                        <p class="text-gray-900 whitespace-no-wrap">{{ supervisorShift.odometer.endOdometer }}km</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-5 py-5 bg-white text-sm">
                                        <p class="text-gray-900 whitespace-no-wrap font-bold">Total</p>
                                    </td>
                                    <td class="px-5 py-5 bg-white text-sm">
                                        <p class="text-gray-900 whitespace-no-wrap">{{ supervisorShift.odometer.endOdometer - supervisorShift.odometer.startOdometer }}km</p>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <loading-animation v-if="isLoadingImages" :size="50" class="m-auto mt-7"></loading-animation>

                        <div v-else class="bg-gray-200 shadow-md rounded-lg m-2">
                            <h3 class="font-semibold text-gray-800 tracking-wide text-center p-3">Odometer Images</h3>

                            <div class="flex items-center flex-wrap justify-center">
                                <div v-for="image in images" class="flex flex-1 p-2 m-2 bg-gray-800 rounded ">
                                    <img class="object-cover h-48 flex-1 hover:opacity-50 transition duration-300" v-img
                                         :src="image" :alt="image">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

import JobSiteCard from "./JobSiteCard"
import {axiosCalls} from "../../../mixins/axiosCallsMixin";
import LoadingAnimation from "../../global/LoadingAnimation";
import moment from "moment";


export default {
    inject: ["eventHub"],

    components: {JobSiteCard, LoadingAnimation},
    props: {
        mapPaneData: {},
        supervisor: {},
        supervisorShift: {},
    },
    watch: {
        supervisorShift: function () { // watch it
            this.getOdometerImages()
        }
    },
    mixins: [axiosCalls],
    data() {
        return {
            openTab: 1,
            images: [],
            isLoadingImages: false
        }
    },

    mounted() {
        this.getOdometerImages()
    },

    methods: {
        getOdometerImages() {
            this.isLoadingImages = true;
            this.asyncGetUserShiftOdometerImages(this.supervisor.id, this.supervisorShift.id).then((data) => {
                this.images = data.data;
                this.isLoadingImages = false;
            });
        },
        openGmapWindow(jobSiteMarker, index) {
            const data = {jobSiteMarker: jobSiteMarker, index: index};
            this.eventHub.$emit("open-gmap-window", data);
        },
        togglePane() {
            this.eventHub.$emit("toggle-data-pane");
        },
        toggleTabs: function (tabNumber) {
            this.openTab = tabNumber
        },
    }
}
</script>
