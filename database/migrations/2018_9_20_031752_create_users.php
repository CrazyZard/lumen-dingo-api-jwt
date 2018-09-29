<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsers extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {

        //用户
        Schema::create('users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('user_id');
            //频率高基本信息
            $table->string('name', 20)->comment('用户名');
            $table->string('password', 400)->comment('密码');
            $table->string('paypassword', 400)->comment('交易密码');
            $table->string('phone', 11)->default(0)->comment('手机号');
            $table->string('id_card', 20)->default(0)->comment('身份证号码');
            $table->string('email', 30)->unique()->default(0)->comment('邮箱');

            //状态参数
            $table->tinyInteger('is_lock')->default(0)->comment('锁定(0|1)');
            $table->tinyInteger('username_one')->default(0)->comment('是否改动过username(0未认证|1已认证|2审核中|3实名认证之后在修改的状态)');
            $table->tinyInteger('is_black')->default(0)->comment('黑名单(0|1)');
            $table->tinyInteger('is_real')->default(0)->comment('是否实名认证(0|1)');
            $table->tinyInteger('email_status')->default(0)->comment('邮箱是否绑定(0|1)');
            $table->tinyInteger('phone_status')->default(0)->comment('手机是否绑定(0|1)');
            $table->tinyInteger('invite_reward_status')->default(0)->comment('是否开启推广奖励(0|1)');
            $table->tinyInteger('qiandao_flag')->default(0)->comment('签到标记，标示是否领过签到红包(0|1)');

            //频率低基本信息
            $table->double('invite_reward_rate', 8, 4)->default(0)->comment('作为邀请人的推广奖励比例');
            $table->tinyInteger('qiandao_count')->default(0)->comment('计数：连续签到天数');
            $table->string('introduce', 20)->default(0)->comment('注册渠道');
            $table->string('invite_userid', 20)->default(0)->comment('邀请人');
            $table->string('invite_code', 20)->default(0)->comment('邀请码');
            $table->string('register_ip', 15)->default(0)->comment('注册ip');
            $table->string('last_login_ip', 15)->default(0)->comment('最后登录ip');
            $table->timestamps();
            $table->softDeletes();
            //索引
            $table->index('phone');
            $table->index('username');
            $table->index('is_black');
            $table->index('id_card');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('users');
    }
}
