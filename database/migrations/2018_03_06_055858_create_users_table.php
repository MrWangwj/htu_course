<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id')->comment('id');
            $table->string('account', 10)->comment('学号');
            $table->string('name', 20)->comment('姓名');
            $table->string('name_py', 20)->comment('姓名拼音');
            $table->enum('sex', ['男', '女'])->default('男')->comment('性别');
            $table->integer('major_id')->comment('专业id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
