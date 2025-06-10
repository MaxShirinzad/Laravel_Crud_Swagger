<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->smallInteger('group_id')->unsigned()->nullable();

            $table->string('Title', 55);
            $table->string('Desc' , '4000')->nullable();
            $table->bigInteger('Price')->unsigned()->default('0');

            $table->text('slug')->nullable(); //عنوان یکتای آگهی که در یوآر ال وارد می شود
            $table->integer('viewCount')->default(0); // تعداد بازدید

            $table->tinyInteger('status')->default('0'); // وضعیت آگهی - باز - بسته - تمام شده و ....
            $table->boolean('IsImage')->default('0'); // آیا عکس دارد؟

//            $table->string('image')->nullable();
            //---------------------------------------------
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
