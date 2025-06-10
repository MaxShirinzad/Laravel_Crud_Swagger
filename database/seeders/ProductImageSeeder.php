<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductImageSeeder extends Seeder
{
     public function run(): void
     {
         //----------------------------------------------
         DB::table('product_images')->truncate(); //delete Old Data in Table

        $ProductImage = array(
            );

         DB::table('product_images')->insert($ProductImage);
    }
}
