<template>
    <li class="px-3 py-2 flex justify-between items-center bg-white cursor-pointer border-b border-l border-gray">
        <div class="flex items-center">
            <div class="relative">
                <avatar :name="supervisor.fullName" :size=8 :borderSize=1></avatar>
                <div class="absolute bottom-4 left-5 h-4 w-4 my-1 border-2 border-white rounded-full z-2"
                     :class="`bg-${ isActive ? 'green': 'red'}-400`"></div>
            </div>
            <div class="ml-3 leading-4 text-gray-700 tracking-wide">
                <p class="font-semibold text-sm">{{ supervisor.fullName }}</p>
                <small class="text-gray-400 text-xs">{{ supervisor.email }}</small>
            </div>
        </div>
        <i class="fa fa-caret-right ml-2"></i>
    </li>
</template>
<script>

import Avatar from "../../global/Avatar";

export default {

    components: {
        Avatar
    },

    props: {
        supervisor: {
            type: Object,
            default: () => ({})

        }
    },

    computed: {
        isActive() {
            return this.supervisor.supervisorShifts.some((e) => e.isActive);
        },
    },
};
</script>
