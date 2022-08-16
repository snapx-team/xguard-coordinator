<template>
    <li class="px-3 py-2 m-1 flex justify-between items-center bg-white cursor-pointer border-b border-l border-gray">
        <div class="flex items-center">
            <div class="w-8 h-8 rounded-full flex flex-none bg-red-500">
                <p class="text-white m-auto font-semibold">{{ jobSiteMarker.label.text }}</p>
            </div>
            <div class="ml-3 leading-4 text-gray-700 tracking-wide">
                <p class="font-semibold text-sm">{{ jobSiteMarker.name }}</p>
                <small class="text-gray-400 text-xs">{{ jobSiteMarker.address }}</small>
                <p v-if="jobSiteMarker.endTime" class="font-semibold text-xs pt-2 text-purple-700">visited between:
                    {{ jobSiteMarker.startTime | moment("HH:mm") }}h - {{ jobSiteMarker.endTime | moment("HH:mm") }}h
                </p>
                <p v-else class="font-semibold text-xs pt-2 text-purple-700">started visit at:
                    {{ jobSiteMarker.startTime | moment("HH:mm") }}h
                </p>
            </div>
        </div>
    </li>
</template>
<script>

import Avatar from "../../global/Avatar";

export default {

    components: {
        Avatar
    },
    props: {
        jobSiteMarker: {
            type: Object,
            default: () => ({})
        }
    }
};
</script>
