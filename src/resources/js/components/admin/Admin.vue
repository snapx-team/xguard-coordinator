<template>
    <div v-if="adminPageData !== null">
        <div class="bg-gray-100 w-full h-64 absolute top-0 rounded-b-lg" style="z-index: -1"></div>

        <div class="flex flex-wrap p-4 pl-10">
            <h3 class="text-3xl text-gray-800 font-bold py-1 pr-8">Admin</h3>
        </div>

        <div class="mx-10 my-3 space-y-5 shadow-xl p-5 bg-white">
            <actions :coordinatorsLength="adminPageData.coordinators.length"></actions>

            <coordinator-list :class="{ 'animate-pulse': loadingCoordinators }"
                           :coordinators="adminPageData.coordinators"></coordinator-list>

            <add-or-edit-coordinator-modal></add-or-edit-coordinator-modal>
        </div>
    </div>
</template>

<script>
import CoordinatorList from "./adminComponents/CoordinatorList.vue";
import Actions from "./adminComponents/Actions.vue";
import AddOrEditCoordinatorModal from "./adminComponents/AddOrEditCoordinatorModal";
import {axiosCalls} from "../../mixins/axiosCallsMixin";

export default {

    inject: ["eventHub"],

    components: {
        AddOrEditCoordinatorModal,
        CoordinatorList,
        Actions,
    },

    mixins: [axiosCalls],

    mounted() {
        this.getAdminPageData();
    },

    data() {
        return {
            filter: "",
            adminPageData: null,
            loadingCoordinators: false,
        };
    },

    created() {
        this.eventHub.$on("save-coordinators", (coordinatorData) => {
            this.saveCoordinators(coordinatorData);
        });

        this.eventHub.$on("delete-coordinator", (coordinatorId) => {
            this.deleteCoordinator(coordinatorId);
        });
    },

    beforeDestroy() {
        this.eventHub.$off('save-coordinators');
        this.eventHub.$off('delete-coordinator');
    },

    methods: {
        saveCoordinators(coordinatorData) {
            this.loadingCoordinators = true;
            const cloneCoordinatorData = {...coordinatorData};
            this.asyncCreateCoordinators(cloneCoordinatorData).then(res => {
                this.asyncGetCoordinators().then((data) => {
                    this.adminPageData.coordinators = data.data;
                    this.loadingCoordinators = false;
                });
            });
        },

        deleteCoordinator(coordinatorId) {
            this.loadingCoordinators = true;
            this.asyncDeleteCoordinator(coordinatorId).then(res => {
                this.asyncGetCoordinators().then((data) => {
                    this.adminPageData.coordinators = data.data;
                    this.loadingCoordinators = false;
                });
            });
        },

        getAdminPageData() {
            this.eventHub.$emit("set-loading-state", true);
            this.asyncGetAdminPageData().then((data) => {
                this.adminPageData = data.data;
                this.eventHub.$emit("set-loading-state", false);
            });
        },
    },
};
</script>


