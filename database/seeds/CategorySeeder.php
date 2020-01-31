<?php

use Illuminate\Database\Seeder;
use App\Category ;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Category::create([
            'person' => 'adult',
            ]);
    
             Category::create([
            'person' => 'kid',
            ]);
    
             Category::create([
            'person' => 'baby',
            ]);
    }
}
