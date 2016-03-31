<?php if (!defined('THINK_PATH')) exit();?><html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta content="text/html; charset=utf8" http-equiv="Content-Type"></meta>
        <title>添加新用户</title>
        <link type="text/css" rel="stylesheet" href="__TMPL__Public/style/style.css"></link>
        <script type="text/javascript" src="__TMPL__/Public/Js/Jquery/jquery.js"></script>
        <script type="text/javascript" src="__TMPL__/Public/Js/Jquery/jquery.form.js"></script>
    </head>
    <body id="page">
        <script language="JavaScript">
			$(function(){
				$('#useredit').ajaxForm({
					success:       complete,  // post-submit callback
					dataType: 'json'
				});

				function complete(data){
					if (data.status==1)
					{
						$('#result').html(data.info).show();
						// 更新列表
						data = data.data;
						window.location.href=data.url;
					}else{
						$('#result').html(data.info).show();
					}
				}
			});
		function passwdCheck() {
			var passwd=document.getElementById("p1");
			var verify_passwd=document.getElementById("p2");
			alert(passwd.value);
			return false;
		}

		function updateinfo(ip){
			$.post("<?php echo U('Pureftpd/getinfo');?>",{'ip':ip},function(data){
			if (data.status==1)
				{
					$('#result').html(data.info).show();
					initselect($('#select_user'), data.data['u'],'uid');
					initselect($('#select_group'), data.data['g'],'gid');
					if($("input[name='Id']").val()==''){
						$("input[name='Dir']").val('');
					}
				}
			else{
					$('#result').html(data.info).show();
					$('#select_user').empty();
					$('#select_user').append("<option value=''>select_user</option>");
					$('#select_group').empty();
					$('#select_group').append("<option value=''>select_group</option>");
				}
			//    initSelect($('#select_user'), data['list']);
			},'json');
		}

		function initselect(slt, rs,type) {    
			slt.empty();    
			if( rs.length > 0 ) {
				for(var i = 0; i < rs.length; i++) {
					slt.append("<option value='"+ rs[i][type] +"'>"+ rs[i]['name'] +"</option>");
				}
			} 
			else slt.append("<option value=''>无法获取</option>");
			}

/*
function showdir() {
    dir=document.forms[0].Dir.value.replace(/\//g,'|');
    $('#dirlist').html('<iframe height="400" width="100%" src="<?php echo U("Pureftpd/getdir",array("ip"=>"'+document.forms[0].At.value+'","path"=>"'+dir+"));?>'">');
}
*/
		function getip(){
			return document.forms[0].At.value;
		}
		function setpath(path){
			document.forms[0].Dir.value=path.replace(/\|/g,'/');
		}
		function mkpasswd(len){
			metastr = "0123456789./" + "abcdefghijklmnopqrstuvwxyz" + "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
			pass='';
			while (pass.length < len) {
				pass += metastr.substr(Math.random()*10000 % (metastr.length),1);
			}
			$('#randpass').html(pass);
			$("input[name='Password']").val(pass);
			$("input[name='confirm_Password']").val(pass);
		}

        </script>
        <form action="<?php echo U("Pureftpd/usersave");?>" method="POST" id="useredit">
            <input type="hidden" value="<?php echo ($user["Id"]); ?>" name="Id">
			<input type="hidden" value="<?php echo ($user["dbserver"]); ?>" name="dbserver">
            <table width="900">
                <tr><td colspan=3 align="center"><div id="result"></div></td></tr>
                <tr>
                    <td width="160" class="border_ltb">所属服务器</td>
                    <td colspan="2" class="border_lrtb">
                        <select style="width: 120px;" name="select_server" onchange="with(document.forms[0]){At.value=this.value;}">
                            <option value="127.0.0.1">select server</option>
                            <?php if(is_array($servers)): $i = 0; $__LIST__ = $servers;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$server): $mod = ($i % 2 );++$i;?><option value="<?php echo ($server["ip"]); ?>"><?php echo ($server["role"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                        <img width="10" hspace="1" height="10" border="0" align="middle" src="__TMPL__Public/images/arrow_right.gif"> 
						<input type="text" readonly="true" value="<?php echo (($user["ip"])?($user["ip"]):'127.0.0.1'); ?>" maxlength="11" size="14" name="At" id="server_ip">
                    </td>
                </tr>
                    <td width="150" class="border_ltb">帐号</td>
                    <td width="570" class="border_lrtb">
                        &nbsp;<input type="text" value="<?php echo ($user["User"]); ?>" maxlength="30" size="10" name="User">
                    </td>
                    <td class="border_rtb"><table width="100%" cellspacing="0" cellpadding="0" border="0">
                            <tr><td>&nbsp;启用</td>
                                <td>
                            <?php if($user['Status'] == 1): ?><input type="checkbox" checked value="<?php echo ($user["Status"]); ?>" name="Status">&nbsp;&nbsp;
                                <?php elseif($user['Status'] == 0 and isset ($user['Status'])): ?>
                                <input type="checkbox" value="1" name="Status">&nbsp;&nbsp;
                                <?php else: ?>
                                <input type="checkbox" checked value="1" name="Status">&nbsp;&nbsp;<?php endif; ?>
                    </td>
                    <td valign="bottom">
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td width="150" class="border_ltb">密码</td>
        <td colspan="2" class="border_lrtb">
            &nbsp;<input type="password" value="" maxlength="64" size="20" name="Password" id="p1">
            &nbsp;<input type="button" onclick="mkpasswd(13)" value="随机生成" class="bt">&nbsp;<span id="randpass"></span>
        </td>
    </tr>
    <tr>
        <td width="150" class="border_ltb">再次确认密码</td>
        <td colspan="2" class="border_lrtb">
            &nbsp;<input type="password" value="" maxlength="64" size="20" name="confirm_Password" id="p2">
        </td>
    </tr>
    <tr>
        <td width="150" class="border_ltb">UID</td>
        <td colspan="2" class="border_lrtb">
            <select style="width: 100px;" onchange="document.forms[0].Uid.value=this.value;" name="select_user" id="select_user">
                <option value="">select user</option>1
            </select>
            <img width="10" hspace="1" height="10" border="0" align="middle" src="__TMPL__Public/images/arrow_right.gif"> <input type="text" value="<?php echo (($user["Uid"])?($user["Uid"]):'99'); ?>" maxlength="11" size="11" name="Uid">
        </td>
    </tr>
    <tr>
        <td width="150" class="border_ltb">GID</td>
        <td colspan="2" class="border_lrtb">
            <select style="width: 100px;" onchange="document.forms[0].Gid.value=this.value;" name="select_group" id="select_group">
                <option value="">select group</option>
            </select>
            <img width="10" hspace="1" height="10" border="0" align="middle" src="__TMPL__Public/images/arrow_right.gif"> <input type="text" value="<?php echo (($user["Gid"])?($user["Gid"]):'99'); ?>" maxlength="11" size="11" name="Gid">
        </td>
    </tr>
    <tr>
        <td width="150" class="border_ltb">主目录</td>
        <td width="570" class="border_ltb">
            &nbsp;<input value="<?php echo ($user["Dir"]); ?>" name="Dir"></td>
        <td align="right" class="border_rtb">
            <input type="button" onclick="javascript:showdir();" value="显示目录列表" class="bt"></input>
        </td>
    </tr>
    <tr>
        <td width="150" class="border_ltb">&nbsp;</td>
        <td id="dirlist" class="border_rtb" colspan="2" >
        </td>
    </tr>
</table>
<table width="900" id="tableInfo">

    <tr>
        <td width="150" class="border_l">上传带宽(KB/s)</td>
        <td width="275" class="border_lr">
            &nbsp;<input type="text" value="<?php echo (($user["ULBandwidth"])?($user["ULBandwidth"]):'0'); ?>" maxlength="10" size="10" name="ULBandwidth">
        </td>
        <td width="150" class="border_lr">文件配额</td>
        <td class="border_r">
            &nbsp;<input type="text" value="<?php echo (($user["QuotaFiles"])?($user["QuotaFiles"]):'0'); ?>" maxlength="10" size="10" name="QuotaFiles">
        </td>
    </tr>
    <tr>
        <td width="150" class="border_ltb">下载带宽(KB/s)</td>
        <td width="275" class="border_lrtb">
            &nbsp;<input type="text" value="<?php echo (($user["DLBandwidth"])?($user["DLBandwidth"]):'0'); ?>" maxlength="10" size="10" name="DLBandwidth">
            </td>
        <td width="150" class="border_lrtb">磁盘配额(MB)</td>
        <td class="border_rtb">
            &nbsp;<input type="text" value="<?php echo (($user["QuotaSize"])?($user["QuotaSize"]):'0'); ?>" maxlength="10" size="10" name="QuotaSize">
            </td>
    </tr>
    <tr>
        <td width="150" class="border_ltb">上传／下载比 [上:下]</td>
        <td width="275" class="border_lrtb">
            &nbsp;<input type="text" value="<?php echo (($user["ULRatio"])?($user["ULRatio"]):'0'); ?>" maxlength="3" size="2" name="ULRatio"> : <input type="text" value="<?php echo (($user["DLRatio"])?($user["DLRatio"]):'0'); ?>" maxlength="3" size="2" name="DLRatio"></td>
        <td colspan="2" class="border_r">
            &nbsp;</td></tr>
    <tr>
        <td width="150" class="border_ltb">IP 地址</td>
        <td width="275" class="border_lrtb">
            &nbsp;<input type="text" value="<?php echo (($user["Ipaddress"])?($user["Ipaddress"]):'*'); ?>" maxlength="20" size="20" name="Ipaddress">
            </td>
        <td colspan="2" class="border_r">
            &nbsp;</td></tr>
    <tr>
        <td width="150" valign="top" class="border_ltb">备注</td>
        <td width="275" class="border_lrtb">
            <table width="100%"><tr><td><textarea wrap="virtual" rows="3" cols="30" name="Comment"><?php echo ($user["note"]); ?></textarea></td></tr></table></td>
        <td colspan="2" class="border_r">
            &nbsp;</td></tr>
    <tr align="right">
        <td height="30" colspan="4" class="border_lrtb">
            <input type="submit" value="保存" name="save" class="bt">&nbsp;
            
        </td>
    </tr>
</table>
</form>
</body>
</html>