const routes = [
    {
        path: '/',
        name: 'index',
        meta: {
            title: 'Gaea'
        },
        component: (resolve) => require(['../pages/index/index.vue'], resolve)
    },
    {
        path: '/login',
        name: 'login',
        component: (resolve) => require(['../pages/login/index.vue'], resolve)
    }
];
export default routes;