import create_visitor from '../visitor/create'
import edit_visitor from '../visitor/edit'
import view_visitor from '../visitor/view'

export default [
  	{
		path : '/visitor/create',
	 	component: create_visitor,
	  	name: 'create_visitor',
	  
    },
  	{
		path : '/visitor/:id/edit',
	  	component: edit_visitor,
	  	name: 'edit_visitor',
	  
    },
  	{
		path : '/visitor/:id/view',
	  	component: view_visitor,
	  	name: 'view_visitor',
    },
]