const resource = '/events';

export default {
    getEvents() {
        return axios.get(`${resource}`);
    },
    getPost(id) {
        return Client.get(`${resource}/${id}`);
    },
    create(payload) {
        return Client.post(`${resource}`, payload);
    },
    update(payload, id) {
        return Client.put(`${resource}/${id}`, payload);
    },
    delete(id) {
        return axios.delete(`${resource}/${id}`)
    },

    // MANY OTHER ENDPOINT RELATED STUFFS
};