import request from '../request'

export function getInfo() {
    return request('get', '/api/user');
}