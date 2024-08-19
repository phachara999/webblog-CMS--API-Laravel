<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_name', function (Blueprint $table) {
            Schema::create('post_categories', function (Blueprint $table) {
                $table->unsignedBigInteger('post_id');
                $table->unsignedBigInteger('cate_id');
                $table->timestamps();
                
                // กำหนดคอลัมน์ post_id และ cate_id เป็น Primary Key พร้อมกัน
                $table->primary(['post_id', 'cate_id']);
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_name');
    }
};
