import create_company from '../company/create'
import edit_company from '../company/edit'
import view_company from '../company/view'


export default [
  	{
		path : '/company/create',
	 	component: create_company,
	  	name: 'create_company',
    },
  	{
		path : '/company/:id/edit',
	  	component: edit_company,
	  	name: 'edit_company',
    },
  	{
		path : '/company/:id/view',
	  	component: view_company,
	  	name: 'view_company',
    },
]