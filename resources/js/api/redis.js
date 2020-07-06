import request from '../request'

/* 按key查询 */
export function keys(body) {
    return request('post', '/api/redis/keys', body);
}

/* 获取Key信息 */
export function get(body) {
    return request('post', '/api/redis/get', body);
}

/* 删除Key */
export function remove(body) {
    return request('post', '/api/redis/delete', body);
}

/* 其他操作 */
export function op(body) {
    return request('post', '/api/redis/op', body);
}