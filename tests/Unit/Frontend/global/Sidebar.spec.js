/* eslint-disable no-undef*/

import {createLocalVue, shallowMount} from '@vue/test-utils';
import Sidebar from '../../../../src/resources/js/components/global/SideBar';
import device from 'vue-device-detector';
import router from '../../../../src/resources/js/router';


const localVue = createLocalVue();
localVue.use(device);
localVue.use(router);

describe('SideBar.vue', () => {

    // testing  toggleSidebar() function

    it('Check if toggle starts true if screen size is over 1200px', () => {

        global.innerWidth = 1201;

        const wrapper = shallowMount(Sidebar, {
            localVue, mocks: {
                $role: 'admin'
            }
        });

        expect(wrapper.vm.isSideBarOpen).toBe(true);
        wrapper.vm.toggleSidebar();
        expect(wrapper.vm.isSideBarOpen).toBe(false);
    });

    it('Checks if toggle starts false if less than 1200px', () => {

        global.innerWidth = 1199;

        const wrapper = shallowMount(Sidebar, {
            localVue, mocks: {
                $role: 'admin'
            }
        });

        expect(wrapper.vm.isSideBarOpen).toBe(false);
        wrapper.vm.toggleSidebar();
        expect(wrapper.vm.isSideBarOpen).toBe(true);
    });
});
