/* eslint-disable no-undef*/

import {createLocalVue, shallowMount} from '@vue/test-utils';
import Dashboard from '../../../../src/resources/js/components/dashboard/Dashboard';
import Vue from 'vue';

const localVue = createLocalVue();

describe('UserCard.vue', () => {

    let wrapper;

    beforeEach(() => {
        wrapper = shallowMount(Dashboard, {
            localVue,
            mocks: {
                $role: 'admin',
            },

            provide() {
                return {
                    eventHub: new Vue()
                };
            },
        });
    });

    it('Checks that fires createCoordinators event', () => {

        let marker = {
            'label': {
                'text': '3',
                'color': '#fff',
                'fontWeight': 'bold',
                'fontSize': '16px'
            },
            'position': {
                'lat': 39.9713524,
                'lng': -75.159036
            },
            'name': 'Location 3',
            'address': 'Test Address'
        };

        const infoPosition = {
            'lat': 39.9713524,
            'lng': -75.159036
        };

        expect(wrapper.vm.showDataPane).toBe(false);
        expect(wrapper.vm.currentMidx).toBe(null);
        expect(wrapper.vm.infoPosition).toBe(null);
        wrapper.vm.openWindow(marker, 1);
        expect(wrapper.vm.showDataPane).toBe(true);
        expect(wrapper.vm.currentMidx).toBe(1);
        expect(wrapper.vm.infoPosition).toMatchObject(infoPosition);
    });
});
