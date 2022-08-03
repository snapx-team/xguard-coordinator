<template>
    <div class="custom-number-input h-12 w-32 text-center">
        <div
            class="border flex flex-row h-12 w-full rounded relative bg-transparent mt-1">
            <a @click="decrement"
               class="text-gray-600  border-l hover:text-gray-700 hover:bg-gray-200 h-full w-24 rounded-r flex pb-1"
               :class="disabled ? 'cursor-not-allowed bg-gray-200': 'cursor-pointer bg-gray-50'">
                <span class="m-auto text-2xl font-semibold">−</span>
            </a>

            <input @input="handleInput($event.target.value)"
                   :value="content"
                   :disabled="disabled"
                   type="number"
                   class="outline-none focus:outline-none text-center w-full font-semibold text-md hover:text-black focus:text-black flex items-center text-gray-700 outline-none"
                   name="custom-input-number"/>

            <a @click="increment"
               class="text-gray-600  border-l hover:text-gray-700 hover:bg-gray-200 h-full w-24 rounded-r flex pb-1"
               :class="disabled ? 'cursor-not-allowed bg-gray-200': 'cursor-pointer bg-gray-50'">
                <span class="m-auto text-2xl font-semibold">+</span>
            </a>
        </div>
    </div>
</template>
<script>

import {axiosCalls} from "../../mixins/axiosCallsMixin";

export default {

    watch: {
        value: function (newVal, oldVal) {
            this.content = newVal;
        }
    },
    props: {
        value: {default: 0},
        disabled: {default: false},
        max: {default: 100},
        min: {default: 1},
    },
    data() {
        return {
            content: this.value
        }
    },
    mixins: [axiosCalls],

    methods: {
        handleInput(value) {
            if (!this.disabled) {
                this.content = parseInt(value)

                if (isNaN(this.content)) {
                    this.content = this.min;
                    this.triggerErrorToast('Input must be a valid number');
                    this.$emit('input', this.content)
                } else {
                    this.content = parseInt(value)
                }

                if (this.content < this.min) {
                    this.content = this.min;
                    this.triggerErrorToast('Input cannot be less than ' + this.min);
                    this.$emit('input', this.content)
                } else if (this.content > this.max) {
                    this.content = this.max;
                    this.triggerErrorToast('Input cannot exceed ' + this.max);
                    this.$emit('input', this.content)
                } else {
                    this.$emit('input', this.content)
                }
            }
        },
        increment() {
            if (!this.disabled) {

                if (this.content + 1 > this.max) {
                    this.content = this.max;
                    this.triggerErrorToast('Input cannot exceed ' + this.max);
                    this.$emit('input', this.content)
                } else {
                    this.$emit('input', ++this.content)
                }
            }
        },
        decrement() {
            if (!this.disabled) {

                if (this.content - 1 < this.min) {
                    this.content = this.min
                    this.triggerErrorToast('Input cannot be less than ' + this.min);
                    this.$emit('input', this.content)
                } else {
                    this.$emit('input', --this.content)
                }
            }
        }
    }
}
</script>

<style scoped>

input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.custom-number-input input:focus {
    outline: none !important;
}

.custom-number-input button:focus {
    outline: none !important;
}

</style>
