import create_event from '../event/create'
import view_event from '../event/view'
import edit_event from '../event/edit'
// import view_company from '../company/view'


export default [
  	{
		path : '/event/create',
	 	component: create_event,
	  	name: 'create_event',
    },
  	// {
	// 	path : '/company/:id/edit',
	//   	component: edit_company,
	//   	name: 'edit_company',
    // },
  	{
		path : '/event/:id/edit',
	  	component: edit_event,
	  	name: 'edit_event',
    },
  	{
		path : '/event/:id/view',
	  	component: view_event,
	  	name: 'view_event',
    },
]