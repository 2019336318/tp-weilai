<?php

namespace app\admin\controller;

use think\Controller;
use think\Session;
use think\Db;

class Login extends Controller
{
    public function index()
    {
        // dump($_SERVER['REMOTE_ADDR']);
        // die;
        // dump($_POST);
        Session::init([
            'prefix'         => '',
            'type'           => '',
            'auto_start'     => true,
        ]);
        //验证

        if (isset($_POST) && !empty($_POST)) {  
            $captcha = input("captcha");
            if (!captcha_check($captcha)) {
                // 验证码错误
                $this->error('验证码错误');
                // exit(json_encode(array('status' => 0, 'msg' => '验证码错误')));
            }else{
                $user = trim($_POST['user']);
                $pwd = trim($_POST['pwd']);
                if(!empty($user)&&!empty($pwd)){
                    $pwd = md5($pwd);
                    $res = Db::name('admin')->where('a_user',$user)->where('a_pass',$pwd)->find();
                    if($res!=''){
                        Session::set('islogin','1');
                        Session::set('user',"{$user}");
                        $time=time();
                        $ip =$_SERVER['REMOTE_ADDR'];
                        DB::name('admin')->where('a_user',$user)->update([
                            'a_lastlogin'=>$time,
                            'a_lastip'=>$ip
                        ]);
                        $this->success("欢迎你{$user}",'admin/Index/index');
                    }else{
                        $this->error('用户名或者密码错误');
                    }

                }else{
                    $this->error('用户名或者密码不能为空');
                }
                // $this->success('欢迎','admin/Index/index');
            }
        }

      
        // die;


        // $this->validate($date,[
        //     'captcha|验证码'=>'require|captcha' //captacha是验证码name名称
        //     ]);
        return $this->fetch('login');
    }
}
