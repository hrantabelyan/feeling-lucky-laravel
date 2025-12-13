import { createRouter, createWebHistory } from 'vue-router';
import Home from '../views/Home.vue';
import Game from '../views/Game.vue';

const routes = [
    {
        path: '/',
        name: 'home',
        component: Home
    },
    {
        path: '/games/:token',
        name: 'game',
        component: Game,
        props: true
    },
    {
        path: '/:pathMatch(.*)*',
        beforeEnter(to, from, next) {
            alert('404');
            window.location.href = window.location.origin + '/404';
            next(false);
        }
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;
