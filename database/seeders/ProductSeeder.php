<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
     public function run(): void
     {
         //----------------------------------------------
         DB::table('products')->truncate(); //delete Old Data in Table

        $products = array(

        );

         DB::table('products')->insert($products);
    }
}
