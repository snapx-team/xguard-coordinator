/* eslint-disable no-undef*/

import { createLocalVue, shallowMount } from '@vue/test-utils';
import Avatar from '../../../../src/resources/js/components/global/Avatar';

const localVue = createLocalVue();

describe('Avatar.vue', () => {

    // testing  getInitials() function

    it('gets initials of user name', () => {
        const myProps ={name: 'Siamak Samie',};
        const wrapper = shallowMount(Avatar, {
            localVue,
            propsData: myProps
        });
        const initials = wrapper.vm.getInitials();
        expect(initials).toEqual('SS');
    });

    it('gets initials if single name', () => {
        const myProps ={name: 'Siamak',};
        const wrapper = shallowMount(Avatar, {
            localVue,
            propsData: myProps
        });
        const initials = wrapper.vm.getInitials();
        expect(initials).toEqual('SI');
    });

    // testing  setInitialsAndBackgroundColor() function

    it('sets initials and backgroundColor', () => {
        const wrapper = shallowMount(Avatar, {localVue});

        wrapper.vm.getInitials = jest.fn().mockImplementation(() => 'Mock Initials');
        wrapper.vm.generateHexColorWithText = jest.fn().mockImplementation(() => 'Mock Color');

        wrapper.vm.setInitialsAndBackgroundColor();

        expect(wrapper.vm.initials).toEqual('Mock Initials');
        expect(wrapper.vm.backgroundColor).toEqual('Mock Color');
    });
});
