<?php
namespace App\Http\Controllers\Admin;


use App\Models\Admin;
use Illuminate\Http\Request;

class  AdminController extends  BaseController {

    protected  $model;
    public function __construct(Admin $admin)
    {
        parent::__construct();
        $this->model = $admin;
    }

    //用户列表
    public function index()
    {
        $users =  $this->model->all();

        var_dump($users);
    }


    public function editPassword(Request $request)
    {
        $validator = \validator($request->all(), [
            'old_password' => 'required',
            'password' => 'required|confirmed|different:old_password',
            'password_confirmation' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }
        $user = $this->user();
        //判断是否是自己
//        $auth = \::once([
//            'email' => $user->email,
//            'password' => $request->get('old_password'),
//        ]);
        $password = app('hash')->make($request->get('password'));
        $user->update(['password' => $password]);
        return $this->response->noContent();

    }
    //post 注册后台管理员
    public function store(Request $request)
    {
        $validator = \validator($request->input(), [
            'email' => 'required|email|unique:admins',
            'username' => 'required|string',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }

        $email = $request->get('email');
        $password = $request->get('password');

        $attributes = [
            'email' => $email,
            'username' => $request->get('username'),
            'password' => app('hash')->make($password),
        ];
        $user = Admin::create($attributes);
        var_dump($user);


        // 用户注册成功后发送邮件
//        dispatch(new SendRegisterEmail($user));

        // 201 with location
//        $location = dingo_route('v1', 'users.show', $user->id);

//        return $this->response->item($user, new UserTransformer())
//            ->header('Location', $location)
//            ->setStatusCode(201);
    }
}