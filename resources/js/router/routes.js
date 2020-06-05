import Layout from '../components/Layout';
import Dashboard from '../pages/dashboard/Dashboard';
import Redis from '../pages/redis-manager/Redis';

const routes = [
    {
        path: '/', name: 'index', meta: {title: 'Gaea'}, component: Layout,
        children: [
            {
                path: '/dashboard', name: 'dashboard', component: Dashboard
            },
            {
                path: '/redis', name: 'redis', component: Redis
            }
        ]
    }
];
export default routes;