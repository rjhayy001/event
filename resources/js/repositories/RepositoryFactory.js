import EventRepository from './EventRepository';
import CompanyRepository from './CompanyRepository';
import VisitorRepository from './VisitorRepository';
import CategoryRepository from './CategoryRepository';

const repositories = {
    'events': EventRepository,
    'companies': CompanyRepository,
    'visitors' : VisitorRepository,
    'categories' : CategoryRepository,
}
export default {
    get: name => repositories[name]
};