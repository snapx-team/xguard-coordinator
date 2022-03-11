import Vue from 'vue';
import Router from 'vue-router';
import Dashboard from '../components/dashboard/Dashboard';
import UserProfile from '../components/users/UserProfile';
import Admin from '../components/admin/admin';

Vue.use(Router);

export default new Router({
    mode: 'history',

    routes: [
        {
            path: '*',
            redirect: 'coordinator/dashboard'
        },
        {
            path: '/coordinator/',
            redirect: 'coordinator/dashboard'
        },
        {
            name: 'dashboard',
            path: '/coordinator/dashboard',
            component: Dashboard
        },

        {
            path: '/coordinator/admin',
            component: Admin
        },

        {
            path: '/coordinator/user-profile',
            component: UserProfile
        }
    ]
});
