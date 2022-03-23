/* eslint-disable no-undef*/

import {createLocalVue, shallowMount} from '@vue/test-utils';
import Footer from '../../../../src/resources/js/components/global/VueFooter';
import axios from 'axios';

const mockFooterInfo = {data: {parent_name: 'Mock Parent Name', year: 'Mock Year', version: 'Mock Version'}};
jest.spyOn(axios, 'get').mockResolvedValue(mockFooterInfo);
const localVue = createLocalVue();

describe('VueFooter.vue', () => {

    it('Check that on mounted, it sets footer values', () => {

        const wrapper = shallowMount(Footer, {
            localVue
        });

        expect(axios.get).toHaveBeenCalledTimes(1);
        expect(axios.get).toHaveBeenCalledWith('get-footer-info');

        wrapper.vm.$nextTick(() => {
            expect(wrapper.vm.parent_name).toEqual('Mock Parent Name');
            expect(wrapper.vm.year).toEqual('Mock Year');
            expect(wrapper.vm.version).toEqual('Mock Version');
        });
    });
});
