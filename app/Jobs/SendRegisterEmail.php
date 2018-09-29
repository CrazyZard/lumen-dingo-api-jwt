<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Support\Facades\Mail;

class SendRegisterEmail extends Job
{
    protected $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle()
    {
        Mail::send('emails.register',['name'=>$this->user->name,'title'=>'绑定邮箱','url'=>url('mail/jump?dcode='.urlencode(authcode($this->user->name . ",". $this->user->email ."," . $_SERVER['REQUEST_TIME'] )))], function ($message) {
            $to = '541306829@qq.com';
            $message ->to($to)->subject('测试邮件');
        });
    }
}
