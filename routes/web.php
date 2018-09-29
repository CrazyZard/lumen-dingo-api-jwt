<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});


//add in header   Accept:application/vnd.lumen.v1+json
$api = app('Dingo\Api\Routing\Router');
////前台用户
$api->version('v1',function ($api){
    //前台操作
    $api->group([
        'prefix'=>'index',
        'namespace' =>'App\Http\Controllers\Index',
        'middleware' => 'api.throttle',//限制次数中间件 throttle
        //限制1分钟20次登陆
        'limit' => 20, 'expires' => 1,
    ],function ($api){
        /*用户 登陆 退出*/
        $api->post('login',['as'=>'index.auth.login','uses'=>'AuthController@login']);//登陆
        $api->post('logout',['as'=>'index.auth.logout','uses'=>'AuthController@logout']);//登出
        $api->post('refresh',['as'=>'index.auth.refresh','uses'=>'AuthController@refresh']);//获取token
        $api->post('register',['as'=>'index.users.store','uses'=>'UserController@store']);//注册用户
        $api->get('users',['as'=>'index.users.index','uses'=>'UserController@index']);//获取用户列表
        $api->get('users/{id}',['as'=>'index.users.show','uses'=>'UserController@show']);//获取用户详细信息
        $api->post('users/pass',['as'=>'index.users.edit_password','uses'=>'UserController@editPassword']);//修改密码
        $api->get('test/{id}','TestController@test');
    });

    //后台管理
    $api->group([
        'prefix'=>'admin',
        'namespace' =>'App\Http\Controllers\Admin',
        'middleware' => 'api.throttle',//限制次数中间件 throttle
        //限制1分钟20次登陆
        'limit' => 20, 'expires' => 1,
    ],function ($api) {
        $api->post('login',['as'=>'admin.auth.login','uses'=>'AuthController@login']);//后台登陆
    });
});

