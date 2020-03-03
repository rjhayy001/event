const resource = '/visitors';

export default {
    get() {
        return axios.get(`${resource}`);
    },
    getVisitor(id) {
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
    activate(item) {
        return axios.post('/activate',item) ;
    },

    // MANY OTHER ENDPOINT RELATED STUFFS
};