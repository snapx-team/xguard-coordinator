/* eslint-disable no-undef*/

import {createLocalVue, shallowMount} from '@vue/test-utils';
import Dashboard from '../../../../src/resources/js/components/dashboard/Dashboard';
import Vue from 'vue';
import {supervisorMockData} from '../../../JsonMockData/supervisorMockData';

const localVue = createLocalVue();

describe('Dashboard.vue', () => {

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

    it('openWindow google map function sets correct data', () => {

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
        expect(wrapper.vm.currentMarkerIndex).toBe(null);
        expect(wrapper.vm.infoPosition).toBe(null);
        wrapper.vm.openWindow(marker, 1);
        expect(wrapper.vm.showDataPane).toBe(true);
        expect(wrapper.vm.currentMarkerIndex).toBe(1);
        expect(wrapper.vm.infoPosition).toMatchObject(infoPosition);
    });

    it('setJobSiteMarkers formats data properly for google maps', () => {

        expect(wrapper.vm.googleMapRefreshKey).toBe(0);
        expect(wrapper.vm.showDataPane).toBe(false);
        wrapper.vm.showDataPaneInfo(supervisorMockData().supervisorShifts);
        expect(wrapper.vm.showDataPane).toBe(true); // data pane must open
        expect(wrapper.vm.googleMapRefreshKey).toBe(1); // refresh key must increment by 1
        expect(wrapper.vm.allJobSiteVisits.length).toBe(3); // an array of all job site visits must be created
        expect(wrapper.vm.allJobSiteVisits.length).toBe(3); // an array of all job site visits must be created

        // testing that job site markers are formatted correctly
        expect(wrapper.vm.jobSiteMarkers).toEqual(
            expect.arrayContaining([
                expect.objectContaining({
                    'address': '9494 boulevard st-laurent',
                    'label': {'color': '#fff', 'fontSize': '14px', 'fontWeight': 'bold', 'text': '3'},
                    'name': '3 - Test',
                    'position': {'lat': 45.5451245, 'lng': -73.6542803}
                }),
            ])
        );
    });
});
