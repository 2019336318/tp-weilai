<?php
//配置文件
use think\Request;

return [
	'view_replace_str'=> array(
		'__CSS__'   => '/static/admin/css',
		'__JS__'    => '/static/admin/js',
        '__IMG__'   => '/static/admin/images',
        '__STA__' =>'/static/admin/',
		//域名
		'__DOMAIN__'=> Request::instance()->domain()
	),
	'default_controller'     => 'Index',
	//开启模板布局 layout
	// 'template' => array(
	// 	'layout_on' => true,
	// 	'layout_name' => 'layout',
	// ),
];