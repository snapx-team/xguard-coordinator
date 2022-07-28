import axios from 'axios';

export const axiosCalls = {

    methods: {

        // Coordinator App Data

        asyncGetAdminPageData() {
            return axios.get('get-admin-page-data').catch((error) => {
                this.loopAllErrorsAsTriggerErrorToast(error);
            });
        },

        asyncGetSupervisorsData(dateRange) {
            return axios.get('get-supervisors-data', {
                params: {
                    start: dateRange[0],
                    end: dateRange[1]
                }}).catch((error) => {
                this.loopAllErrorsAsTriggerErrorToast(error);
            });
        },

        // Coordinators

        asyncGetCoordinatorProfile() {
            return axios.get('get-coordinator-profile').catch((error) => {
                this.loopAllErrorsAsTriggerErrorToast(error);
            });
        },

        asyncGetAllUsers() {
            return axios.get('get-all-users').catch((error) => {
                this.loopAllErrorsAsTriggerErrorToast(error);
            });
        },

        asyncGetSomeUsers(searchTerm) {
            if (searchTerm === '') {
                return axios.get('get-all-users').catch((error) => {
                    this.loopAllErrorsAsTriggerErrorToast(error);
                });
            }
            return axios.get('get-some-users/' + searchTerm).catch((error) => {
                this.loopAllErrorsAsTriggerErrorToast(error);
            });
        },

        asyncGetCoordinators() {
            return axios.get('get-coordinators').catch((error) => {
                this.loopAllErrorsAsTriggerErrorToast(error);
            });
        },

        asyncCreateCoordinators(coordinatorData) {
            return axios.post('create-coordinators', coordinatorData).then(() => {
                this.triggerSuccessToast('Coordinator Added!');
            }).catch((error) => {
                this.loopAllErrorsAsTriggerErrorToast(error);
            });
        },

        asyncDeleteCoordinator(employeeId) {
            return axios.post('delete-coordinator/' + employeeId).then(() => {
                this.triggerSuccessToast('Coordinator Removed');
            }).catch((error) => {
                this.loopAllErrorsAsTriggerErrorToast(error);
            });
        },

        asyncGetUserShiftOdometerImages(userId, shiftId) {
            return axios.get('get-user-shift-odometer-images/' + userId + '/' + shiftId).catch((error) => {
                this.loopAllErrorsAsTriggerErrorToast(error);
            });
        },

        // Location

        asyncGetLocationPathData(supervisorShiftId) {
            return axios.get('get-location-pings/' + supervisorShiftId).catch((error) => {
                this.loopAllErrorsAsTriggerErrorToast(error);
            });
        },

        //Triggers
        triggerSuccessToast(message) {
            this.$toast.success(message, {
                position: 'bottom-right',
                timeout: 5000,
                closeOnClick: true,
                pauseOnFocusLoss: true,
                pauseOnHover: true,
                draggable: true,
                draggablePercent: 0.6,
                showCloseButtonOnHover: false,
                hideProgressBar: false,
                closeButton: 'button',
                icon: true,
                rtl: false
            });
        },

        triggerErrorToast(message) {
            this.$toast.error(message, {
                position: 'bottom-right',
                timeout: 5000,
                closeOnClick: true,
                pauseOnFocusLoss: true,
                pauseOnHover: true,
                draggable: true,
                draggablePercent: 0.6,
                showCloseButtonOnHover: false,
                hideProgressBar: false,
                closeButton: 'button',
                icon: true,
                rtl: false
            });
        },

        triggerInfoToast(message) {
            this.$toast.info(message, {
                position: 'bottom-right',
                timeout: 5000,
                closeOnClick: true,
                pauseOnFocusLoss: true,
                pauseOnHover: true,
                draggable: true,
                draggablePercent: 0.6,
                showCloseButtonOnHover: false,
                hideProgressBar: false,
                closeButton: 'button',
                icon: true,
                rtl: false
            });
        },

        // Loop all errors

        loopAllErrorsAsTriggerErrorToast(errorResponse) {
            if ('response' in errorResponse && 'errors' in errorResponse.response.data) {
                let errors = [];
                Object.values(errorResponse.response.data.errors).map(error => {
                    errors = errors.concat(error);
                });
                errors.forEach(error => this.triggerErrorToast(error));
            } else if (errorResponse.response.data.message) {
                this.triggerErrorToast(errorResponse.response.data.message);
            }
        }
    }
};
