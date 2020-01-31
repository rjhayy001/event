<?php

use Illuminate\Database\Seeder;
use App\General ;

class GeneralSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        General::create([
            'field' => 'background-image',
            'value' => '' ,
            ]);
          General::create([
            'field' => 'logo',
            'value' => '' ,
            ]);
          General::create([
            'field' => 'presentation',
            'value' => '' ,
            ]);
          General::create([
            'field' => 'programme',
            'value' => '' ,
            ]);
          General::create([
            'field' => 'tarifs',
            'value' => '' ,
            ]);
          General::create([
            'field' => 'plan',
            'value' => '' ,
            ]);
          General::create([
            'field' => 'restauration',
            'value' => '' ,
            ]);
          General::create([
            'field' => 'highlights',
            'value' => '' ,
            ]);
          General::create([
            'field' => 'event_logo',
            'value' => '' ,
            ]);
    
    }
}
