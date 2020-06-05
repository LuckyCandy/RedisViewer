import ViewUI from 'view-design';
import ViewRouter from 'vue-router';
import router from './router';
import 'view-design/dist/styles/iview.css';
import App from './app.vue';

window.Vue = require('vue');
window.EBUS = new Vue();
Vue.use(ViewUI);
Vue.use(ViewRouter);
Vue.config.productionTip = false;

new Vue({
    el: '#app',
    router: router,
    render: h => h(App)
});
