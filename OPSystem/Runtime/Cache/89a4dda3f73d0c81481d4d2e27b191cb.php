<?php if (!defined('THINK_PATH')) exit();?><html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta content="text/html; charset=utf8" http-equiv="Content-Type">
        <title>服务器列表</title>
        <link type="text/css" rel="stylesheet" href="__TMPL__Public/style/style.css">
    </head>
    <body id="page">
        <script>
            function del (id){
                action = confirm("删除后无法恢复！确认删除该信息吗？");
                if (action != "0")
                {
                    window.location="<?php echo U('Pureftpd/serverdel');?>"+"?id="+id;
                }
            }
        </script>
        <h2><a href="<?php echo U("Pureftpd/serveredit");?>">添加服务器</a></h2>
        <table width="100%" cellspacing="0" cellpadding="0" border="0">
            <!--tr><td colspan="4"><div id="result"></div></td></tr>-->
            <tr>
                <th width="20%" >服务器角色</th>
                <th width="20%">内网IP</th>
                <th>操作</th>
            </tr>
            <?php if(is_array($server)): $i = 0; $__LIST__ = $server;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$server): $mod = ($i % 2 );++$i;?><tr>
                    <td><?php echo ($server["role"]); ?></td>
                    <td><?php echo ($server["ip"]); ?></td>                 
                    <td><a href="<?php echo U("Pureftpd/serveredit",array('id'=>$server['id']));?>">修改</a>&nbsp;|&nbsp;<a href="javascript:void(0)" onclick="del(<?php echo ($server["id"]); ?>)" style="cursor:pointer">删除</a>&nbsp;|&nbsp;<a href="<?php echo U("Pureftpd/serveronline",array('ip'=>$server['ip']));?>">在线用户</a></td>
                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
			<tr><th colspan="4"><?php echo ($page); ?></th></tr>
        </table>



    </body></html>