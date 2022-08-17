<template>
    <li class="px-3 py-2 flex justify-between items-center bg-white cursor-pointer border-b border-l border-gray">
        <div class="flex items-center">
            <div class="ml-3 leading-4 text-gray-700 tracking-wide">
                <p class="font-semibold text-sm">{{ dateRangeText }}</p>
                <small class="text-gray-400 text-xs">
                    visited <b>{{ shift.jobSiteVisits.length }}</b> sites
                </small>
            </div>
        </div>
    </li>
</template>
<script>

import moment from "moment";

export default {

    props: {
        shift: {
            type: Object,
            default: () => ({})
        }
    },
    computed: {
        dateRangeText(){
            return this.shift.endTime? moment(this.shift.startTime).format('MMM DD, HH:mm') + " - " + moment(this.shift.endTime).format('MMM DD, HH:mm')
                : moment(this.shift.startTime).format('MMM DD, HH:mm') + " - in progress" ;
        }
    },
};
</script>
