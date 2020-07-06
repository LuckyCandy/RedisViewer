import Layout from '../components/Layout';
import Dashboard from '../pages/dashboard/Dashboard';
import Redis from '../pages/redis-manager/Redis';
import RedisAction from '../pages/redis-manager/Action';

const routes = [
    {
        path: '/', name: 'index', meta: {title: 'Gaea'}, component: Layout, redirect: '/dashboard',
        children: [
            {
                path: '/dashboard', name: 'dashboard', component: Dashboard
            },
            {
                path: '/redis', name: 'redis', component: Redis,
            },
            {
                path: '/redis/action', name: 'redis-action', component: RedisAction
            }
        ]
    }
];
export default routes;