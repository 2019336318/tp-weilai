<?php

namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Request; //请求
use app\index\model\Category as Cate; //使用模型命名空间


class Common extends Controller
{
	public function __construct()
	{
		parent::__construct(); //继承父类的构造函数
		$this->header();
	}

	//公共头部信息
	public function header()
	{
		$nav = Db::name('category')->where('pid', 0)->select();

		//查询入口文件
		$base_file = Request::instance()->baseFile();
		// dump(Request::instance()->param());

		// 获取地址栏请求参数
		$param = Request::instance()->param();
		$category = isset($param['category']) ? $param['category'] : 1;
		$cate =	isset($param['cate']) ? $param['cate'] : 0;
		$col = Request::instance()->controller();

		//实例化 Category 模型
		$cateModel = new Cate();
		//查询二级分类
		$sub_nav = $cateModel->where('pid', $category)->select();

		//查询一级分类对应的二级分类 id，存储到数组
		foreach ($sub_nav as $item) {
			$sub_ids[] = $item['cid'];
		}
		// dump($sub_ids);

		$data = array(
			'sub_nav' => $sub_nav,
			'cate' => $cate,
			'category' => $category,
			'nav' => $nav,
			'base_file' => $base_file,
			'col' => $col
		);

		$this->assign('head', $data);
		// $this->assign('nav',$data);
	}
}
