<?php

namespace App\Http\Controllers\Index;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Servers\Api\MailServer;
//use Illuminate\Support\Facades\Mail;
//use Mail;

class MailController  extends Controller
{
    public function __construct(MailServer $mailServer)
    {
        $this->server = $mailServer;
    }

    //发送注册链接点击完成绑定邮箱
    public function send(Request $request)
    {
        $input = $request->input();
        unset($request);
        $result = $this->server->send($input);
        unset($input);
        return $this->responseResult($result);
    }

    public function send_sms(Request $request)
    {
        $input = $request->input();
        unset($request);
        $result = $this->server->send_sms($input);
        unset($input);
        return $this->responseResult($result);
    }

    public function jump(Request $request)
    {
        $input = $request->input();
        unset($request);
        $result = $this->server->jump($input);
        unset($input);
        return $this->responseResult($result);
    }



    //更换绑定邮箱
    public function update(Request $request)
    {
        $input = json_decode($request->input('data'),true);
        $email = $input['email'];
        $title = "更换绑定邮箱";
        $username = $input['username'];
        //$user_id = $input['id'];
        $time = $_SERVER['REQUEST_TIME'];
        $_add = urlencode(authcode($username . ",". $email ."," . $time )); //加了urlencode加密和urldecode解密的时候有时候不成功，原因未知。
        $url = url('api/mail/jump?dcode='.$_add);

        $flag = Mail::send('emails.update',['title'=>$title,'username'=>$username,'url'=>$url],function($message) use($email){
            $to = $email;
            $message ->to($to)->subject('更换邮箱');
        });

        if(count(Mail::failures()) > 0){
            return $this->responseResult(['code' => ['x00001','email']]);
        }else{
            $result['status'] = 1;
            $result['data'] = 1;
            $result['message'] = "发送邮件成功，请查收";
            return json_encode($result);
        }
    }
}