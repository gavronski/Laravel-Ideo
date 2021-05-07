<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TreeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->truncate();
        DB::table('categories')->insert([
        ['id'=> 1,'parent_id' => 0,'text' => 'categories'],
        ['id'=> 2,'parent_id' => 1,'text' => 'AGD'],
        ['id'=> 3,'parent_id' => 1,'text' => 'Consoles'],
        ['id'=> 4,'parent_id' => 1,'text' => 'Computers'],
        ['id'=> 5,'parent_id' => 4,'text' => 'Laptops'],
        ['id'=> 6,'parent_id' => 5,'text' => 'DELL'],
        ['id'=> 7,'parent_id' => 6,'text' => 'HP'],
        ['id'=> 8,'parent_id' => 2,'text' => 'GPS'],
        ['id'=> 9,'parent_id' => 1,'text' => 'Phones'],
        ['id'=> 10,'parent_id' => 9,'text' => 'Xiaomi'],
        ['id'=> 11,'parent_id' => 9,'text' => 'iPhone'],
        ['id'=> 12,'parent_id' => 9,'text' => 'Samusng'],
        ['id'=> 13,'parent_id' => 9,'text' => 'Sony']]);
    }
}
