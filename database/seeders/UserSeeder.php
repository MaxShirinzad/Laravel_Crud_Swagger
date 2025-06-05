<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        //----------------------------------------------
        DB::table('users')->truncate(); //delete Old Data in Table

        $users = array(
            array('id' => '1','name' => 'مدیر ارشد','phone' => NULL,'profile' => NULL,'type_id' => '1','user_category_id' => NULL,'email' => 'superadmin@site.com','password' => '$2y$10$xBHdIvAOZ6cK0cuBtozOb.hK54bsMa33CTrkf8x8O/xozAe6iHI5.','isActivated' => '1','image' => NULL,'remember_token' => '04PkR3EfAJWmhaHpRN7db3FR8PwqG9PmDhXLRZ7x9HETx5OnZQ3BV3Gn88L1','deleted_at' => NULL,'created_at' => '2022-08-11 20:40:54','updated_at' => '2024-03-25 09:28:36'),
            array('id' => '2','name' => 'مدیر 2','phone' => NULL,'profile' => NULL,'type_id' => '2','user_category_id' => NULL,'email' => 'admin@site.com','password' => '$2y$10$xBHdIvAOZ6cK0cuBtozOb.hK54bsMa33CTrkf8x8O/xozAe6iHI5.','isActivated' => '0','image' => NULL,'remember_token' => 'LppyVQrjTz0r0OUw1OIFMYxP3MeluYpUZDDk4iH8VD8SwMIqxfgim1vLqqq5','deleted_at' => NULL,'created_at' => '2022-08-11 20:40:54','updated_at' => '2023-04-10 09:50:26'),
            array('id' => '3','name' => 'کاربر','phone' => NULL,'profile' => NULL,'type_id' => '3','user_category_id' => NULL,'email' => 'user@site.com','password' => '$2y$10$z1VZGiQzrFaeV4e6LESPgeJiTsDb1VEfNqkvXLuDRiKI8SzuCPssu','isActivated' => '0','image' => NULL,'remember_token' => 'LyPAaTG7CHnL5MzCLy35Wqu1haDuYGhGHuOdxOY5IXpgDv3M8H7GosOFQyPI','deleted_at' => NULL,'created_at' => '2022-08-11 20:40:54','updated_at' => '2022-08-11 20:40:54'),
            array('id' => '20','name' => 'Mehdi','phone' => NULL,'profile' => NULL,'type_id' => '1','user_category_id' => NULL,'email' => 'mehdi163000@gmail.com','password' => '$2y$10$ezRdjiM6wwbbQt.0fWxVRuJ2CFsN/kmHpzWqZh.THIYpw5djaBJ8K','isActivated' => '0','image' => NULL,'remember_token' => 'NIWS6akPUG9LjJArD8UIuSjtEQUc5o7a0B6nMs0lIjoxK56SJCBc3Qb47yYO','deleted_at' => NULL,'created_at' => '2023-05-06 19:45:28','updated_at' => '2024-02-12 22:42:12'),
            array('id' => '21','name' => 'Test','phone' => NULL,'profile' => NULL,'type_id' => '3','user_category_id' => NULL,'email' => 'test23@test.com','password' => '$2y$10$1X93cS8jLD.kxdzNMdbVJ.jzBHy5gBLbuiFTuVFjLuXD4JrncW8N.','isActivated' => '0','image' => NULL,'remember_token' => NULL,'deleted_at' => NULL,'created_at' => '2023-05-06 19:47:03','updated_at' => '2023-09-04 22:13:51'),
            array('id' => '46','name' => 'vvvvvvv','phone' => NULL,'profile' => NULL,'type_id' => '3','user_category_id' => '2','email' => 'max.shirinzad@gmail.com','password' => '$2y$10$8u/1yOslZvgqeh.cascJ9.3pTX8cRs.wYvcKGCAgrx5tBZ.sAqpYa','isActivated' => '1','image' => '','remember_token' => NULL,'deleted_at' => NULL,'created_at' => '2023-07-03 22:43:54','updated_at' => '2024-02-03 21:15:16'),
            array('id' => '98','name' => 'test1','phone' => NULL,'profile' => NULL,'type_id' => '3','user_category_id' => NULL,'email' => 'test1@site.com','password' => '$2y$10$j9AgIIToyvPUhLt002f6YOUrJWf14iJxmkTgXcN54LuKbbRKZD/Ge','isActivated' => '1','image' => NULL,'remember_token' => NULL,'deleted_at' => NULL,'created_at' => '2024-03-25 10:42:25','updated_at' => '2024-03-25 10:43:24'),
            array('id' => '99','name' => 'test2','phone' => NULL,'profile' => NULL,'type_id' => '3','user_category_id' => NULL,'email' => 'test2@site.com','password' => '$2y$10$3pH89jq4Ll2T0AYPNU8y9eKubK/MZrlMfUoqtK1vs0aFQT77bsHv6','isActivated' => '1','image' => NULL,'remember_token' => NULL,'deleted_at' => NULL,'created_at' => '2024-03-25 10:49:21','updated_at' => '2024-03-25 10:50:43')
        );


        DB::table('users')->insert($users);
        //---------------------------------------
    }
}
