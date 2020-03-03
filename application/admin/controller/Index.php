<?php
namespace app\admin\controller;

use app\admin\controller\Base;
use think\Db;
class Index extends Base
{

    public function index()
    {
        $name = session('user');
        $user_info = Db::name('admin')->where('a_user',$name)->find();
        return $this->fetch('index',[
            'user'=>$user_info
        ]);
    }

   public function welcome(){

    $name = session('user');
        $user_info = Db::name('admin')->where('a_user',$name)->find();
        return $this->fetch('welcome',[
            'user'=>$user_info
        ]);
   }
   public function logout(){
       $user=session('user');
       session(null);
       $this->success("再见{$user}",'admin/login/index');
   }
}
