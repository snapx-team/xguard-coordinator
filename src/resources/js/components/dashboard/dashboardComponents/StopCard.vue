<template>
    <li class="px-3 py-2 m-1 flex justify-between items-center bg-white cursor-pointer border-b border-l border-gray">
        <div class="flex items-center">

            <div class="w-8 h-8 rounded-full flex flex-none bg-gray-700">
                <p class="text-white m-auto font-semibold">{{ stopMarker.label.text }}</p>
            </div>

            <div class="ml-3 leading-4 text-gray-700 tracking-wide">
                <p class="font-semibold text-sm">Stopped roughly {{ stopMarker.totalTime }} minutes</p>
                <p class="font-semibold text-sm pb-2">Between: {{ stopMarker.startTime | moment("HH:mm") }}h - {{ stopMarker.endTime | moment("HH:mm") }}h</p>
                <p class="text-gray-400 text-xs pb-2">coordinates:</p>
                <p class="text-gray-400 text-xs pb-2">{{ stopMarker.coordinates }} </p>
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
        stopMarker: {
            type: Object,
            default: () => ({})

        }
    }
};
</script>
