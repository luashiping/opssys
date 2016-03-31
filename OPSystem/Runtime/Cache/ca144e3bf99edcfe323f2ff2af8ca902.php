<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
<title>gtobal运维系统管理界面</title>
<link href="__TMPL__Public/style/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="__TMPL__Public/style/js.js"></script>
<script type="text/javascript" src="__TMPL__/Public/Js/Jquery/jquery.js"></script>
</head>

<body id="index">
<h1 class='home_h1'>gtobal运维管理系统</h1>
<div id="userInfo">你好,<?php echo $_SESSION['LOGINROLE']; echo $_SESSION['LOGINUSER'];?>! 今天是<?php echo date("Y-m-d H:i:s",time());?></div>
<div class="home_div">
<ul id="globalNav">
	
	<li class='dd'><a href="<?php echo U("Pureftpd/serverlist");?>" target="frameBord">服务器管理</a></li>
	<li><a href="javascript:void(0)">Pureftpd</a>
		<ul>
            <li class="style" ><a href="<?php echo U("Pureftpd/userlist",array("dbserver"=>'204'));?>" target="frameBord">204用户数据库</a></li>
			<li class="style" ><a href="<?php echo U("Pureftpd/userlist",array("dbserver"=>'14'));?>" target="frameBord">14用户数据库</a></li>
            <li class="style" ><a href="<?php echo U("Pureftpd/userlist",array("dbserver"=>'233'));?>" target="frameBord">内部用户数据库</a></li>
        </ul>
	</li>
    <li><a href="javascript:void(0)">docker</a>
		<ul>
			<li class="style"><a href="<?php echo U("Docker/clist");?>" target="frameBord">containers</a></li>
            <li class="style"><a href="<?php echo U("Docker/reposlist");?>" target="frameBord">本地镜像</a></li>
		</ul>
	</li>
	<li><a href="javascript:void(0)">gtobal代码发布</a>
		<ul>
			<li class="style"><a href="<?php echo U("Code/buildlist");?>" target="frameBord">build</a></li>
			<li class="style"><a href="<?php echo U("Code/status");?>" target="frameBord">当前运行状态</a></li>
			<li class="style"><a href="<?php echo U("Code/adminfabu");?>" target="frameBord">分支开发</a></li>
		</ul>
	</li>
	<li><a href="javascript:void(0)">爽淘代码发布</a>
		<ul>
			<li class="style"><a href="<?php echo U("CodePush/sfabutest");?>" target="frameBord">正式发布</a></li>
		</ul>
	</li>
    
	<li><a href="javascript:void(0)">OPS系统管理</a>
		<ul>
			<li class="style"><a href="<?php echo U("Admin/userlist");?>" target="frameBord">用户管理</a></li>
			<!--li><a href="#">权限管理</a></li>-->
		</ul>
    </li>
	<li><a href="<?php echo U("Index/logout");?>">安全退出</a></li>
</ul>
</div>
<iframe id="frameBord" name="frameBord" frameborder="0" width="100%" height="100%" src=""></iframe>
<script>
/*
	$('.style').click(function(){
		$(this).css("background","#FF0000");
	})
*/
</script>
</body>
</html>