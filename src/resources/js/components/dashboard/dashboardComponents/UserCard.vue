<template>
    <li class="px-3 py-2 flex justify-between items-center bg-white cursor-pointer border-b border-l border-gray">
        <div class="flex items-center">
            <avatar :name="user.name" :size=8 :borderSize=2 borderColor="gray"></avatar>
            <div class="ml-3 leading-4 text-gray-700 tracking-wide">
                <p class="font-semibold text-sm">{{ user.name }}</p>
                <small class="text-gray-400 text-xs">{{ user.email }}</small>
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
        user: {
            type: Object,
            default: () => ({})

        }
    }
};
</script>
