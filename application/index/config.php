<?php

use think\Request;

//配置文件
return array(
	// 视图输出字符串内容替换
	'view_replace_str'=> array(
		'__CSS__'   => '/static/home/css',
		'__JS__'    => '/static/home/js',
		'__IMG__'   => '/static/home/images',
		//域名
		'__DOMAIN__'=> Request::instance()->domain()
	),

	//开启模板布局 layout
	'template' => array(
		'layout_on' => true,
		'layout_name' => 'layout',
	),
);