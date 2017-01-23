<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('name');
            $table->string('job')->nullable();
            $table->string('job_status')->nullable();
            $table->string('email')->nullable();
            $table->string('first_name')->nullable();                           // 名
            $table->string('last_name')->nullable();                            // 姓
            $table->string('real_name')->nullable();                            // 真实姓名
            $table->string('avatar_url');                           // 头像链接（原始尺寸）
            $table->boolean('use_gravatar')->default(false);        // 使用Gravatar头像
            $table->integer('age')->nullable();                                 // 年龄
            $table->string('gender')->default('unspecified');       // 性别 [unspecified, secrecy, male, female]
            $table->string('status')->nullable();
            $table->string('birthday')->nullable();                             // 生日
            $table->string('country')->nullable();                              // 国家
            $table->string('city')->nullable();                                 // 城市
            $table->string('address')->nullable();                              // 地址
            $table->string('phone')->nullable();                                // 电话
            $table->string('company')->nullable();                              // 公司
            $table->string('website')->nullable();                              // 主页
            $table->string('bio')->nullable();                                  // 简介
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_profiles');
    }
}
