<?php

use Illuminate\Database\Seeder;
use App\Details ;
use App\Rule ;

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
            'value' => 'Quimper Evenements' ,
        ]);
        Details::create([
            'fields' => 'Place',
            'value' => 'France' ,
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
        
    }
}
