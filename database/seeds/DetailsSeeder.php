<?php

use Illuminate\Database\Seeder;
use App\Details ;
use App\Rule ;
use Illuminate\Support\Facades\Hash;
use App\Visitor ;

class DetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Details::create([
            'fields' => 'Owner',
            'value' => '' ,
            'details' => 'static'
        ]);
        Details::create([
            'fields' => 'Address',
            'value' => '' ,
            'details' => 'static'
        ]);
        Details::create([
            'fields' => 'Zip Code',
            'value' => '' ,
            'details' => 'static'
        ]);
        Details::create([
            'fields' => 'City',
            'value' => '' ,
            'details' => 'static'
        ]);
        Details::create([
            'fields' => 'Country',
            'value' => '' ,
            'details' => 'static'
        ]);
        Details::create([
            'fields' => 'Phone Number',
            'value' => '' ,
            'details' => 'static'
        ]);
        Details::create([
            'fields' => 'Email',
            'value' => '' ,
            'details' => 'static'
        ]);
        Rule::create([
            'fields' => 'Name',
            'required' => true 
        ]);
        Rule::create([
            'fields' => 'Phone',
            'required' => true 
        ]);
        Rule::create([
            'fields' => 'Email',
            'required' => true 
        ]);
        Rule::create([
            'fields' => 'Username',
            'required' => true 
        ]);
        Rule::create([
            'fields' => 'Password',
            'required' => true 
        ]);
        Rule::create([
            'fields' => 'Private Access',
            'required' => true 
        ]);

        Visitor::create([
            'name' => 'test',
            'phone' => '123123123' ,
            'email' => 'test@test.com',
            'username' => 'test123',
            'password' => Hash::make('123123'),
        ]);
        
    }
}
