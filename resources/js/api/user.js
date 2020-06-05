import request from '../request'

/* 获取用户信息 */
export function getInfo() {
    return request('get', '/api/user');
}

/* 更新用户信息 */
export function update(body) {
    return request('post', '/api/user/update', body);
}