
import axios from 'axios';
import {errcode} from "./errcode";
import router from '../router'
import qs from 'qs'
import ViewUI from 'view-design';


axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.baseURL = '';

/* 请求拦截器添加访问凭证 */
axios.interceptors.request.use(
    config => {
        ViewUI.LoadingBar.start();
        config.timeout = 10 * 1000;
        config.headers.Authorization = 'Bearer ' + window.localStorage.getItem('gaea.token');

        if (
            config.method.toLocaleUpperCase() === 'POST' ||
            config.method.toLocaleUpperCase() === 'PUT' ||
            config.method.toLocaleUpperCase() === 'DELETE'
        ) {
            config.data = qs.stringify(config.data);
        }
        return config;
    },
    error => {
        return Promise.reject(error);
    }
);

/* 响应拦截器 */
axios.interceptors.response.use(
    response => {
        if (response.data.code === 200) {
            ViewUI.LoadingBar.finish();
            return Promise.resolve(response.data)
        }
        /* 如果需要重新登录,则调起登录弹框 */
        if (response.data.code === 403) {
            ViewUI.LoadingBar.finish();
            setTimeout(() => {
                EBUS.$emit('EVENT-USER-UNLOGIN', '');
            }, 2000);
        }
        ViewUI.LoadingBar.error();
        ViewUI.Message['error']({
            background: true,
            content: response.data.msg
        });
        return Promise.reject(response.data);
    },
    error => {
        ViewUI.LoadingBar.error();
        if(error && error.response){
            let res = {};
            res.code = error.response.status;
            res.msg = errcode(error.response.status, error.response);
            ViewUI.Message.error(res.msg);
            return Promise.reject(res);
        }else if(error && error.request && error.message.includes('timeout')){
            let res = {};
            res.code = 408;
            res.msg = errcode(408, error.response);
            ViewUI.Message.error(res.msg);
            return Promise.reject(res);
        }
        ViewUI.Message.error('Unknown Error Occur!');
        return Promise.reject(error);
    }
);

export default function(method, url, data) {
    method = method.toLocaleString();
    return axios.request({
        method,
        url,
        data
    });
};

