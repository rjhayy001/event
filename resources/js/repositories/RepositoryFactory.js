import EventRepository from './EventRepository';
import CompanyRepository from './CompanyRepository';
import VisitorRepository from './VisitorRepository';

const repositories = {
    'events': EventRepository,
    'companies': CompanyRepository,
    'visitors' : VisitorRepository
}
export default {
    get: name => repositories[name]
};