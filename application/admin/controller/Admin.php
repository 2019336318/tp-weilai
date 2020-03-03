<?php

namespace app\admin\controller;

use app\admin\controller\Base;
use think\Db;

class Admin extends Base
{
    public function index()
    {

        $a_list = Db::name('admin')->where(1)->select();

        return  $this->fetch('admin-list', [
            'list' => $a_list
        ]);
    }
    public function add()
    {
        // dump($_POST);
        if (isset($_POST) && !empty($_POST)) {
            return json([
                'msg' => 'aaa'
            ]);
        }

        // die;


        return  $this->fetch('admin-add');
    }

    public function insert()
    {
        // dump($_POST);
        $user = trim($_POST['adminName']);
        $pwd = md5(trim($_POST['password']));
        $pwd2 = md5(trim($_POST['password2']));
        if ($pwd != $pwd2) {
            return json([

                'code' => 0,
                'msg' => '两次密码不一致'

            ]);
        }
        $time = time();
        $res = DB::name('admin')->insert([
            'a_user' => $user,
            'a_pass' => $pwd,
            'a_salf' => 1,
            'a_lastlogin' => $time,
            'a_lastip' => '127.0.0.1',
            'a_isshow' => 1,
        ]);
        if ($res == 1) {
            return json([
                'code' => 1,
                'msg' => '成功'
            ]);
        } else {
            return json([
                [
                    'code' => 0,
                    'msg' => '失败'
                ]
            ]);
        }
        // return  json($_POST);
    }

    public function del()
    {
        // dump($_POST);
        $id = $_POST['id'];
        $res = Db::name('admin')->where('a_id', $id)->delete();
        if ($res != 0) {
            return json([
                'msg' => '删除成功'
            ]);
        }else{
            return json([
                'msg' => '删除失败'
            ]);
        }
    }
}
