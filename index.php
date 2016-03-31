<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

header("Content-type: text/html; charset=utf-8");

// 定义ThinkPHP框架路径
define('THINK_PATH', './ThinkPHP/');
//定义项目名称和路径
define('APP_PATH', './OPSystem/');
define('APP_NAME', 'OPSystem');
//Include Composer's autoloader
require_once __DIR__.'/vendor/autoload.php';
// 加载框架入口文件
require(THINK_PATH."/ThinkPHP.php");
//实例化一个网站应用实例
//App::run();
?>
