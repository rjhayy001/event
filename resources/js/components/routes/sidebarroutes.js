import show_category from '../settings/category/show'
import show_visitor from '../visitor/show'
import show_company from '../company/show'
import show_event from '../event/show'
import show_dashboard from '../dashboard/show'

export default [
  	{
		path : '/category',
		component: show_category,
		name: 'category',
	  
    },
  	{
		path : '/visitor',
	  	component: show_visitor,
	  	name: 'visitor',
	  
    },
  	{
		path : '/company',
	  	component: show_company,
		name: 'company',
	  
    },
  	{
		path : '/event',
	  	component: show_event,
	  	name: 'event',
	  
    },
  	{
		path : '/dashboard',
	  	component: show_dashboard,
	  	name: 'dashboard',
	  
    },
]