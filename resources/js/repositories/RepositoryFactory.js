import EventRepository from './EventRepository';
// import UserRepository from './UserRepository';

const repositories = {
    'events': EventRepository,
}
export default {
    get: name => repositories[name]
};