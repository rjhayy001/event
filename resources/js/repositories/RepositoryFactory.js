import EventRepository from './EventRepository';
import CompanyRepository from './CompanyRepository';

const repositories = {
    'events': EventRepository,
    'companies': CompanyRepository,
}
export default {
    get: name => repositories[name]
};