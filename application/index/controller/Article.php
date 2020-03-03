<?php

namespace app\index\controller;

use app\index\controller\Common;
use think\Db;

class Article extends Common
{
	public function index($category = 1, $cate = 0, $id)
	{
		$db = new Db;
		// $id = $id;
		// echo $id;
		$di = $db::name('article')
			->where('aid', "{$id}")
			->find();
		// dump($di);
		//查询二级分类
		$sub_nav = $db::name('category')->where('pid', $category)->select();

		//查询一级分类对应的二级分类 id，存储到数组
		foreach ($sub_nav as $item) {
			$sub_ids[] = $item['cid'];
		}
		// dump($sub_ids);


		if ($cate == 0) {
			// 上一篇id
			$prev = $db::name('article')
				->where('cid', 'in', $sub_ids)
				->where('aid', '<', $id)
				->order('aid', 'DESC')
				->limit(1)
				->value('aid');
			// dump($prev);


			// 下一篇文章id
			$next = $db::name('article')
				->where('cid', 'in', $sub_ids)
				->where('aid', '>', $id)
				->limit(1)
				->value('aid');
			// dump($next);
		} else {
			$prev = $db::name('article')
				->where('cid', $cate)
				->where('aid', '<', $id)
				->order('aid', 'DESC')
				->limit(1)
				->value('aid');
			// dump($prev);


			// 下一篇文章id
			$next = $db::name('article')
				->where('cid', $cate)
				->where('aid','>', $id)
				->limit(1)
				->value('aid');
			// dump($next);
		}

		return $this->fetch('article', [
			'di' => $di,
			'next' => $next,
			'prev' => $prev,
			'cate' => $cate,
			'category' => $category
			
		]);
	}
}
