import { createRouter, createWebHistory } from 'vue-router';
import ProductsView from '@/views/ProductsView.vue';

const routes = [
    {
        path: '/',
        name: '',
        redirect: '/products'
    },
    {
        path: '/products',
        name: 'products',
        component: ProductsView
    }
];

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes
})

export default router;