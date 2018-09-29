<?php
namespace App\Http\Controllers\Index;

use App\Models\User;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use App\Models\Authorization;
use App\Jobs\SendRegisterEmail;
class  UserController extends  BaseController {

    protected  $model;
    public function __construct(User $user)
    {
        $this->middleware('auth:index', ['except' => ['store']]);
        $this->model = $user;
    }

    //用户列表
    public function index()
    {
        $users = User::paginate(1);

        return $this->response->paginator($users, new UserTransformer());
    }

    //修改密码
    public function editPassword(Request $request)
    {
        $validator = \validator($request->all(), [
            'old_password' => 'required',
            'password' => 'required|confirmed|different:old_password',
            'password_confirmation' => 'required|same:password',
        ],[
            'old_password.required' => '旧密码必填',
            'password.required' => '新密码必填',
            'password.different' => '旧密码与新密码相同',
            'password.confirmed' => '密码确认不匹配',
            'password_confirmation.required' => '请在输入一遍密码',
            'password_confirmation.same' => '两次密码输入不同',
        ]);
        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }
        $user = $this->user();
        //判断是否是自己
        $auth = \Auth::once([
            'email' => $user->email,
            'password' => $request->get('old_password'),
        ]);
        if (! $auth) {
            return $this->response->errorUnauthorized();
        }
        $password = app('hash')->make($request->get('password'));
        $user->update(['password' => $password]);
        return $this->response->noContent();
    }
    //post 注册后台管理员
    public function store(Request $request)
    {
        $validator = \validator($request->input(), [
            'email' => 'required|email|unique:users',
            'name' => 'required|string',
            'password' => 'required',
        ],[
            'email.required'=>'邮箱必填',
            'email.unique'=>'邮箱重复',
            'name.required'=>'名字必填',
            'password.required'=>'密码必填',
        ]);

        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }

        $email = $request->get('email');
        $password = $request->get('password');

        $attributes = [
            'email' => $email,
            'name' => $request->get('name'),
            'password' => app('hash')->make($password),
        ];

        $user = User::create($attributes);
        // 用户注册成功后发送邮件
        dispatch((new SendRegisterEmail($user))->delay(5));

        // 201 with location
        $location = dingo_route('v1', 'index.users.show', $user->id);
        return $this->response->item($user, new UserTransformer())
            ->header('Location', $location)
            ->setStatusCode(201);
    }
    //  get 某个用户信息
    public function show($id)
    {
        $user = $this->model->findOrFail($id);
        return $this->response->item($user,new UserTransformer());
    }

    // get 当前用户信息
    public function userShow()
    {
        return $this->response->item($this->user(), new UserTransformer());
    }

}