import request from '../request'

export function test() {
    return request('get', '/api');
}