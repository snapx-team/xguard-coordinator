<template>
    <div v-if="dashboardData !== null">
        <div class="bg-gray-100 w-full h-64 absolute top-0 rounded-b-lg" style="z-index: -1"></div>

        <div class="flex flex-wrap p-4 pl-10">
            <h3 class="text-3xl text-gray-800 font-bold py-1 pr-8">Dashboard</h3>
        </div>

        <div class="mx-10 my-3 space-y-5 shadow-xl p-5 bg-white">
            <p>This is where the magic happens</p>
        </div>
    </div>
</template>

<script>

import {axiosCalls} from "../../mixins/axiosCallsMixin";

export default {

    inject: ["eventHub"],

    mixins: [axiosCalls],

    mounted() {
        this.getDashboardData();
    },

    data() {
        return {
            dashboardData: null,
        };
    },

    methods: {
        getDashboardData() {
            this.eventHub.$emit("set-loading-state", true);
            this.asyncGetDashboardData().then((data) => {
                this.dashboardData = data.data;
                this.eventHub.$emit("set-loading-state", false);
            });
        },
    },
};
</script>


