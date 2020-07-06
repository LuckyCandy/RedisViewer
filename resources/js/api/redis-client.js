import request from '../request'

/* 获取服务列表 */
export function list() {
    return request('get', '/api/redis/client/list');
}

/* 创建服务信息 */
export function create(body) {
    return request('post', '/api/redis/client/create', body);
}

/* 更新服务信息 */
export function update(id, body) {
    return request('post', '/api/redis/client/update/' + id, body);
}

/* 删除服务信息 */
export function remove(id) {
    return request('put', '/api/redis/client/delete/' + id, body);
}

/* 切换当前服务 */
export function set_on_work(id) {
    return request('get', '/api/redis/client/choose/' + id);
}