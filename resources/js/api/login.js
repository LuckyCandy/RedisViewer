import request from '../request'

/* 用户登录 */
export function login(body) {
    return request('post', '/api/login', body);
}