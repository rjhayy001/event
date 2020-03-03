const resource = '/categories';

export default {
    get() {
        return axios.get(`${resource}`);
    },
    getCategory(id) {
        return axios.get(`${resource}/${id}/edit`);
    },
    create(payload) {
        return axios.post(`${resource}`, payload);
    },
    update(payload, id) {
        return axios.put(`${resource}/${id}`, payload);
    },
    delete(id) {
        return axios.delete(`${resource}/${id}`)
    },

    // MANY OTHER ENDPOINT RELATED STUFFS
};