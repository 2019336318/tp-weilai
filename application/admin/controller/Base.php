<?php

namespace app\admin\controller;

use think\Controller;
use think\Session;
use think\Db;

class Base extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->cate();
        $this->is_login();
    }
    public function cate()
    {
        $cate = DB::name('category')->where(1)->select();
        $cate_array = array();
        foreach ($cate as $key => $item) {
            if ($item['pid'] == 0) {
                $cate_array[$key] = $item;
            }
            foreach ($cate as $v) {
                if ($v['pid'] > 0 && $v['pid'] == $item['cid']) {
                    $cate_array[$key]['sub'][] = $v;
                }
            }
        }
        $this->assign('cate', $cate_array);
    }

    public function is_login()
    {
        Session::init([
            'prefix'         => '',
            'type'           => '',
            'auto_start'     => true,
            'expire' => 7200
        ]);

        $login = Session::get('islogin');
        $user = Session::get('user');
        if ($login == 1) {
        } else {

            $this->error('未登录', 'admin/Login/index');
        }
    }
}
