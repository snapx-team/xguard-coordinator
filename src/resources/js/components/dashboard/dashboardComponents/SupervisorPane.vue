<template>
    <div class="flex flex-wrap flex-col">
        <div class="flex justify-between p-2 bg-indigo-800 border-b">
            <div class="flex items-center">
                <button
                    class="bg-indigo-700 hover:bg-indigo-500 transition duration-150 ease-in-out rounded px-2 py-1 mr-2"
                    v-if="panelName === panelNames.shifts"
                    @click="previousPanel">
                    <i class="fas fa-arrow-left text-white"></i>
                </button>
                <h1 class="text-white">{{ panelName }}</h1>
            </div>
            <div>
                <button
                    @click="togglePane"
                    class="focus:outline-none flex flex-col items-center text-gray-400 hover:text-gray-500 transition duration-150 ease-in-out pl-8"
                    type="button">
                    <i class="fas fa-times"></i>
                    <span
                        class="text-xs font-semibold text-center leading-3 uppercase p-1">Esc</span>
                </button>
            </div>
        </div>
        <div class="py-3 overflow-auto" style="height:550px;">
            <transition mode="out-in"
                        :name="this.transitionName">
                <div class="block"
                     v-if="panelName === panelNames.supervisors"
                     key="1">
                    <user-card
                        v-for="supervisor in supervisorsData"
                        :key="supervisor.id"
                        :supervisor="supervisor"
                        @click.native="showSupervisorShifts(supervisor)"
                    ></user-card>
                </div>
                <div class="block"
                     v-if="panelName === panelNames.shifts" key="2">
                    <shiftCard
                        v-for="(shift, index) in selectedSupervisor.supervisorShifts"
                        :shift=shift
                        :key="index"
                        @click.native="showDataPaneInfo(shift)">
                        {{ shift.startTime }}
                    </shiftCard>
                </div>
            </transition>
        </div>
    </div>
</template>
<script>
import ShiftCard from "./ShiftCard"
import UserCard from "./UserCard"
import {axiosCalls} from "../../../mixins/axiosCallsMixin";

export default {
    inject: ["eventHub"],
    components: {ShiftCard, UserCard},
    props: {
        supervisorsData: {},
        supervisorPaneIsVisible: true
    },
    mixins: [axiosCalls],

    mounted() {
        this.panelName = this.panelNames.supervisors;
        this.transitionName = this.transitionNames.next;
    },

    data() {
        return {
            selectedSupervisor: {},
            transitionName: "",
            panelName: "",
            panelNames: {
                supervisors: "Supervisors",
                shifts: "Shifts"
            },
            transitionNames: {
                next: "next",
                previous: "previous"
            },
        };
    },

    methods: {
        nextPanel() {
            this.transitionName = this.transitionNames.next;
            this.panelName = this.panelNames.shifts;

        },
        previousPanel() {
            this.transitionName = this.transitionNames.previous;
            this.panelName = this.panelNames.supervisors;
        },

        showSupervisorShifts(supervisor) {
            if (supervisor.supervisorShifts.length > 0) {
                this.selectedSupervisor = supervisor;
                this.nextPanel();
            } else {
                this.triggerInfoToast('This user has no shifts in this time span');
            }
        },
        showDataPaneInfo(supervisorShift) {
            const supervisorShiftData = {
                supervisorShift: supervisorShift,
                supervisor: this.selectedSupervisor
            }
            this.eventHub.$emit("show-data-pane-info", supervisorShiftData);
        },
        togglePane() {
            this.eventHub.$emit("toggle-supervisor-pane");
        },
    }
}
</script>

<style scoped>
.list-inline-item {
    cursor: pointer;
}

.next-leave, .previous-leave {
    opacity: 1;
}

.next-leave-active, .previous-leave-active {
    transition: all .2s ease
}

.next-leave-to {
    opacity: 0;
    transform: translateX(-50px);
}

.next-enter {
    opacity: 0;
    transform: translateX(50px);
}

.next-enter-active, .previous-enter-active {
    transition: all .2s ease
}

.next-enter-to, .previous-enter-to {
    opacity: 1;
}

.previous-leave-to {
    opacity: 0;
    transform: translateX(50px);
}

.previous-enter {
    opacity: 0;
    transform: translateX(-50px);
}
</style>
