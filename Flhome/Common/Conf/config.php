<?php
require_once 'common.inc.php';//引入配置文件

return array(
    //'配置项'=>'配置值'
    
    //数据库配置信息
    'DB_TYPE'   => 'mysql', // 数据库类型
    'DB_HOST'   => 'localhost', // 服务器地址
    'DB_NAME'   => 'fl72p', // 数据库名
    'DB_USER'   => 'root', // 用户名
    'DB_PWD'    => '123456', // 密码
    'DB_PORT'   => 3306, // 端口
    'DB_PARAMS' => array(), // 数据库连接参数
    'DB_PREFIX' => 'fl_', // 数据库表前缀 
    'DB_CHARSET'=> 'utf8', // 字符集
    //'DB_DEBUG'  =>  TRUE, // 数据库调试模式 开启后可以记录SQL日志
	
    /* 'APP_SUB_DOMAIN_DEPLOY' => 1, // 开启子域名配置
    'APP_SUB_DOMAIN_RULES'  => array(
        'm.72p.org'=>'Wap/Index',  // m.72p.org域名指向Wap模块
    ), */
    
    // 显示页面Trace信息
    //'SHOW_PAGE_TRACE' =>true,
    
    // 开启路由，如果规则含有/，记得加转义
    'URL_ROUTER_ON' => true,
    'URL_ROUTE_RULES' => array(
        'tags'                          => array('Home/Index/tags',array('ext'=>'html')),
        'search'                        => 'Home/Index/search',
		'/^cat([0-9]+)$/'               => array('Home/Index/category?cat=:1',array('ext'=>'html')),
        '/^cat([0-9]+)\/([0-9]+)$/'     => array('Home/Index/category?cat=:1&page=:2',array('ext'=>'html')),
        '/^cat([0-9]+)\/id([0-9]+)$/'   => array('Home/Index/detail?cat=:1&id=:2',array('ext'=>'html')),
		'/^tag([0-9]+)$/'               => array('Home/Index/tag?tag=:1',array('ext'=>'html')),
        '/^tag([0-9]+)\/([0-9]+)$/'     => array('Home/Index/tag?tag=:1&page=:2',array('ext'=>'html')),
        '/^([a-z0-9]+)$/'               => array('Home/Index/page?id=:1',array('ext'=>'html'))
    ),
);