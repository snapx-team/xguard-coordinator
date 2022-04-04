/* eslint-disable no-undef*/

import { createLocalVue, shallowMount } from '@vue/test-utils';
import UserCard from '../../../../src/resources/js/components/dashboard/dashboardComponents/UserCard';
import Vue from 'vue';

const localVue = createLocalVue();

describe('UserCard.vue', () => {

    let wrapper;

    beforeEach(() => {
        // eslint-disable-next-line no-unused-vars
        wrapper = shallowMount(UserCard, {
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
