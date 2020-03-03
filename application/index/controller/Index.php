<?php

namespace app\index\controller;

use app\index\controller\Common;
use think\Db;


class Index extends Common
{
	/**
	 * @param int $category 一级分类id
	 * @param int $cate     二级分类id
	 * @return mixed
	 */
	public function index($category = 1, $cate = 0)
	{
		$db = new Db();
		// $res = $db::query(" SELECT * FROM `pre_category` JOIN pre_article ON pre_category.`cid`=pre_article.`cid` WHERE pid={$category}");
		// dump($res);
		// 查询文章
		
		$cur = (!empty($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
		$limit = 3;
		$offset = ($cur - 1) * $limit;
		$condtion = "{$offset},{$limit}";
		if ($cate == 0) {
			$info = $db::name('article')
				->where('pid', "{$category}")
				->limit($condtion)
				->order('pubtime', 'DESC')
				->join('pre_category', 'pre_category.`cid`=pre_article.`cid`')
				->select();

			$count = $db::name('article')
				->where('pid', "{$category}")
				->join('pre_category', 'pre_category.`cid`=pre_article.`cid`')
				->count();
		} else {
			$info = $db::name('article')
				->where('cid', "{$cate}")
				->limit($condtion)
				->order('pubtime', 'DESC')
				->select();


			$count = $db::name('article')
				->where('cid', "{$cate}")
				->count();
		}
		// $count = count($info);
		// dump($category);
		// dump($cate);
		// dump($info);
		// dump($count);

		$page = $db::name('article')->paginate($limit, $count);


		return $this->fetch('index', [
			'info' => $info,
			'page' => $page,
			'cate'=>$cate
		]);
	}
}
