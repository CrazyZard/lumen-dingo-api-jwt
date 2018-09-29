<?php
namespace App\Servers\Common;

use App\Repositories\Api\MailRepository;
use App\Servers\Common\CommonServer;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;

class MailServer extends CommonServer
{
    public function __construct(MailRepository $mailRepository)
    {
        $this->mailRepository = $mailRepository;
    }

    //发送注册邮箱
    public function send($input)
    {
        $email = $input['email'];
        $title = "绑定邮箱";
        $username = $input['username'];
        //$user_id = $input['id'];
        $time = $_SERVER['REQUEST_TIME'];
        $_add = urlencode($this->authcode($username . ",". $email ."," . $time )); //加了urlencode加密和urldecode解密的时候有时候不成功，原因未知。
        $url = url('mail/jump?dcode='.$_add);

        $flag = Mail::send('emails.register',['title'=>$title,'username'=>$username,'url'=>$url],function($message) use($email){
            $to = $email;
            $message ->to($to)->subject('注册邮件');
        });


        if(count(Mail::failures()) > 0){
            return $this->responseResult(['code' => ['x00001','email']]);
        }else{
            $res = 1;
            return ['发送邮件成功，请查收',$res];
        }
    }


    //绑定邮箱
    public function jump($input)
    {
        //这边不对input['dcode']进行urldecode处理,可能在获取的时候已经urldecode了
        $params = explode(",", $this->authcode(trim($input['dcode']), "decrypt"));

        $arr = array();
        //$arr['user_id'] = $params[0];
        $arr['username'] = $params[0];
        $arr['email'] = $params[1];
        $arr['time'] = $params[2];
        $arr['active_time'] = 600;//过期时间定为10分钟
        if($arr['time']+$arr['active_time']<$_SERVER['REQUEST_TIME']){
            return ['code' => ['x00002','email']];
        }
        $res = $this->auth($arr);

        if(!$res){
            return ['code' => ['x00004','email']];
        }else{
            return ['邮箱绑定成功',$res];
        }
        //return $this->responseResult($result);
    }

    public function auth($input){

        $result = $this->mailRepository->auth($input);
        return $result;

    }

    //发送注册邮箱(验证码形式)
    public function send_sms($input)
    {
        $email = $input['email'];

        if($email == ""){
            return ['code' => ['x00003','email']];
        }
        $title = "绑定邮箱";

        if(isset($input['username'])){
            $username = $input['username'];
        }else{
            $username = "";
        }

        $msg = rand(10000,99999);
        $content = "你的验证码为：".$msg."【贷齐乐】";	//内容

        $flag = Mail::send('emails.register_sms',['title'=>$title,'username'=>$username,'content'=>$content],function($message) use($email){
            $to = $email;
            $message ->to($to)->subject('注册邮件');
        });

        if(count(Mail::failures()) > 0){
            return ['code' => ['x00001','email']];
        }else{
            //session(['valicode' => $msg]);
            Redis::setex('valicode_'.$username,600,$msg);
            $res = 1;
            return ['发送邮件成功，请查收',$res];
        }
    }
}