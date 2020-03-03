<?php

namespace app\index\controller;

use app\index\controller\Common;
use think\Db;

class Search extends Common
{
	public function index($q)
	{
		// dump($q);
		
		$key = $q;
		$cur = (!empty($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
		$limit = 2;
		$offset = ($cur - 1) * $limit;
		$condtion = "{$offset},{$limit}";
		if($key!=''){
			$info =Db::name('article')
			->where('title','like',"%{$key}%")
			->where('content','like',"%{$key}%")
			->limit($condtion)
			->select();
			$count =Db::name('article')
			->where('title','like',"%{$key}%")
			->where('content','like',"%{$key}%")
			->count();
		}
		// dump($info);
		// dump($count);
		$page = Db::name('article')->paginate($limit, $count,[
			'query' => [
				'q'=>$key
			]
		]);
		return $this->fetch('search',[
			'count'=>$count,
			'info'=>$info,
			'page'=>$page,
			'key'=>$key
		]);
	}
}
