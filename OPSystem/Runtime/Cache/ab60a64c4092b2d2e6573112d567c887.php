<?php if (!defined('THINK_PATH')) exit();?><html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta content="text/html; charset=utf8" http-equiv="Content-Type">
        <title>用户列表</title>
        <link type="text/css" rel="stylesheet" href="__TMPL__Public/style/style.css"></link>
        <script type="text/javascript" src="__TMPL__/Public/Js/Jquery/jquery.js"></script>
    </head>
    <body id="page">
        <script>
			function del (id,dbserver,obj){
                action = confirm("删除后无法恢复！确认删除该信息吗？");
                if (action != "0")
                {
					$.get("<?php echo U('Pureftpd/userdel');?>",
					{
						id:id,
						dbserver:dbserver
					},
					function(data) {
						if(data.status==1){
							obj.parents('tr').remove();
							$('#result').html(data.info).show();
						}else{
							alert('删除失败');
						}
					});
                }
            }

              /*function lock (id,dbserver,obj){
                    $.get("<?php echo U('Pureftpd/lock');?>", 
					{
						id:id,
						dbserver:dbserver
					}, 
					function(data){
						if(data.status==1){
							$(obj).html('锁定').show();
                        }else if (data.status==0){
							$(obj).html('解锁').show();
                        }
							}else{
							$('#result').html(data.info).show();
                    }
                });
			}*/
			
            function clearup(id,all){
                all=arguments[1]?arguments[1]:false;
                action = confirm("清除后再无法恢复！确定要删除吗？");
                if (action != "0")
                {
                    if(all){
                        //window.location.href="<?php echo U("Pureftpd/userdel",array('clearupall'=>'1"'));?>;
                    }else{
                        //window.location.href="<?php echo U("Pureftpd/userdel",array('clearup'=>'1','id'=>'"+id'));?>;
                    }
                }
            }
        </script>
        <h2>
			<a href="<?php echo U('Pureftpd/useredit',array('dbserver'=>$dbserver));?>">添加新用户</a>
			<div style="float:right;margin-top: -7px">
				<form style="border:1px solid #ddd;padding:2px;background:#ddd" action="<?php echo U("Pureftpd/userlist",array('dbserver'=>$dbserver));?>" method="post" id="SearchformID">
					<input style="border:none;background:#fff" type="text" name="keyword" value="">
					<select onchange="with(document.forms[0]){at.value=this.value;}">
						<option value ="note">备注</option>
						<option value ="ip">所属服务器</option>
					</select>
					<input type="hidden" value="note" name="at">
					<input class="sbtn" type="submit" value="搜索"/>
				</form>
			</div>
		</h2>
        <div id="search">
		</div>
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
            <tr><td colspan="9"><div id="result" align="center"></div></td></tr>
            <tr>
                <th width="70"><a href="<?php echo U("Pureftpd/userlist",array("order"=>'ip'));?>" style="TEXT-DECORATION:none;">所属服务器</a></th>
				<th width="70"><a href="<?php echo U("Pureftpd/userlist",array("order"=>'ip'));?>" style="TEXT-DECORATION:none;">备注</a></th>
                <th width="70"><a href="<?php echo U("Pureftpd/userlist",array("order"=>'username'));?>" style="TEXT-DECORATION:none;">帐号</a></th>
                <th width="20"><a href="<?php echo U("Pureftpd/userlist",array("order"=>'uid'));?>" style="TEXT-DECORATION:none;">UID</a></th>
                <th width="20"><a href="<?php echo U("Pureftpd/userlist",array("order"=>'gid'));?>" style="TEXT-DECORATION:none;">GUID</a></th>
                <th width="250">主目录</th>
                <th width="60">上传带宽(KB/s)</th>
                <th width="60">下载带宽(KB/s)</th>
                <th width="150">管理操作</th>
            </tr>
            <?php if(is_array($users)): $i = 0; $__LIST__ = $users;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$user): $mod = ($i % 2 );++$i;?><tr>
                    <td><?php echo ($user["ip"]); ?></td>
					<td><?php echo ($user["note"]); ?></td>
                    <td><?php echo ($user["User"]); ?></td>
                    <td><?php echo ($user["Uid"]); ?></td>
                    <td><?php echo ($user["Gid"]); ?></td>
                    <td><?php echo ($user["Dir"]); ?></td>
                    <td><?php echo ($user["ULBandwidth"]); ?></td>
                    <td><?php echo ($user["DLBandwidth"]); ?></td>   
                <?php if($clearup == 1): ?><td><a style="cursor:pointer" onclick="return clearup(<?php echo ($user["Id"]); ?>)">彻底删除</a>&nbsp;|&nbsp;<a href="<?php echo U('Pureftpd/userresume',array('id'=>$user['Id']));?>">撤销删除</a></td>
                <?php else: ?>
                    <td>
						<a href="<?php echo U('Pureftpd/useredit',array('id'=>$user['Id'],'dbserver'=>$dbserver));?>">修改</a>&nbsp;
						<a href="javascript:void(0)" style="cursor:pointer" onclick="del(<?php echo ($user["Id"]); ?>,<?php echo ($dbserver); ?>,$(this))">删除</a>&nbsp;
						<a href="javascript:void(0)" style="cursor:pointer" id="lock" onclick="lock(<?php echo ($user["Id"]); ?>,<?php echo ($dbserver); ?>,$(this))"><?php if(($user['enable'] == 1) ): ?>锁定<?php else: ?>解锁<?php endif; ?></a>&nbsp;
						<a href="<?php echo U('Pureftpd/ftpverify',array('user'=>$user['User'],'ip'=>$user['ip'],'dbserver'=>$dbserver));?>" target="_blank">连接测试</a>&nbsp;
					</td><?php endif; ?>
                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
            <?php if($clearup == 1): ?><tr><td colspan="9" align="right"><a style="cursor:pointer" onclick="return clearup(-1,true)">清空已删除用户</td></tr><?php endif; ?>
            <tr><th colspan="9"><?php echo ($page); ?></th></tr>
        </table>
    </body>
</html>