import request from '../request'

export function login(body) {
    return request('post', '/api/login', body);
}