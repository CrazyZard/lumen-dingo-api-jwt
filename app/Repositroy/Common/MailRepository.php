<?php
namespace App\Repositories\Common;

use App\Models\Mail;
use App\Common\PHPMailer;
use Illuminate\Support\Facades\Auth;

class MailRepository
{

    public function auth($input)
    {
        $result = json_decode( json_encode($this->getDetail($input['username'])),true);

        $res = $this->updateByWhere(['username' => $input['username']], array("email"=>$input['email'],"email_status"=>1));
        if($res){
            return ['邮箱修改成功',$res];
        }else{
            return ['邮箱修改失败',$res];
        }
    }




}
