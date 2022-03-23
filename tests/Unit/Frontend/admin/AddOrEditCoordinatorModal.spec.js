/* eslint-disable no-undef*/

import {createLocalVue, shallowMount} from '@vue/test-utils';
import AddOrEditCoordinatorModal
    from '../../../../src/resources/js/components/admin/adminComponents/AddOrEditCoordinatorModal';
import Vue from 'vue';
import {eventNames} from '../../../../src/resources/js/enums/eventNames';

const localVue = createLocalVue();

describe('AddOrEditCoordinatorModal.vue', () => {

    // testing createCoordinators event

    let wrapper;

    beforeEach(() => {
        wrapper = shallowMount(AddOrEditCoordinatorModal, {
            localVue,
            mocks: {
                $role: 'admin',
            },

            provide() {
                return {
                    eventHub: new Vue(),
                };
            },
        });
    });

    it('Checks that when an createCoordinators event is emitted without any option, it opens modals and sets default values', () => {

        expect(wrapper.vm.modalOpen).toBe(false);
        wrapper.vm.eventHub.$emit(eventNames.createCoordinators);
        expect(wrapper.vm.modalOpen).toBe(true);
        expect(wrapper.vm.coordinatorData.role).toEqual('employee');
        expect(wrapper.vm.coordinatorData.id).toEqual(null);
        expect(wrapper.vm.isEdit).toEqual(false);

    });

    it('Checks that when an createCoordinators event is emitted without any option, it opens modals and sets default values', () => {

        const coordinator = {
            role: 'test',
            id: 10,
            user: {id: 100}
        };

        expect(wrapper.vm.modalOpen).toBe(false);
        wrapper.vm.eventHub.$emit(eventNames.createCoordinators, coordinator);
        expect(wrapper.vm.modalOpen).toBe(true);
        expect(wrapper.vm.coordinatorData.role).toEqual('test');
        expect(wrapper.vm.coordinatorData.id).toEqual(10);
        expect(wrapper.vm.coordinatorData.selectedUsers).toEqual([{id: 100}]);
        expect(wrapper.vm.coordinatorData.user).toEqual({id: 100});
        expect(wrapper.vm.isEdit).toEqual(true);

    });

    // testing saveCoordinators function

    it('Checks that when you save a coordinator the modal closes', () => {

        wrapper.vm.coordinatorData = {
            id: null,
            role: 'employee',
            selectedUsers: []
        };
        wrapper.vm.modalOpen = true;
        expect(wrapper.vm.modalOpen).toBe(true);
        wrapper.vm.saveCoordinators();
        expect(wrapper.vm.modalOpen).toBe(false);
    });

    it('Checks that when try to save a coordinator without selecting a user, a toast message is shown', () => {

        wrapper.vm.coordinatorData = {
            id: null,
            role: 'employee',
        };

        wrapper.vm.triggerErrorToast = jest.fn();
        wrapper.vm.modalOpen = true;
        expect(wrapper.vm.modalOpen).toBe(true);
        wrapper.vm.saveCoordinators();
        expect(wrapper.vm.modalOpen).toBe(true);
        expect(wrapper.vm.triggerErrorToast).toHaveBeenCalled();
    });

    // testing deleteCoordinators function

    it('Checks that when you delete a coordinator the modal closes', () => {

        wrapper.vm.modalOpen = true;
        expect(wrapper.vm.modalOpen).toBe(true);
        wrapper.vm.deleteCoordinator();
        expect(wrapper.vm.modalOpen).toBe(false);
    });
});
