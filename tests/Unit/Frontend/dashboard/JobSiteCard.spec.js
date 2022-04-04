/* eslint-disable no-undef*/

import { createLocalVue, shallowMount } from '@vue/test-utils';
import JobSiteCard from '../../../../src/resources/js/components/dashboard/dashboardComponents/JobSiteCard';
import Vue from 'vue';

const localVue = createLocalVue();

describe('UserCard.vue', () => {

    let wrapper;

    beforeEach(() => {
        // eslint-disable-next-line no-unused-vars
        wrapper = shallowMount(JobSiteCard, {
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

    it('Todo', () => {
        expect(true).toBe(true);
    });
});
