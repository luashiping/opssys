<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf8" />
        <title>gtobal运维系统</title>
        <link href="__TMPL__/Public/style/style.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="__TMPL__/Public/Js/Jquery/jquery.js"></script>
        <script type="text/javascript" src="__TMPL__/Public/Js/Jquery/jquery.form.js"></script>
    </head>

    <body id="login">
        <script>
			$(document).ready(function() { 
				var options = { 
					//target:        '',   // target element(s) to be updated with server response 
					//beforeSubmit:  showRequest,  // pre-submit callback 
					success:       complete,  // post-submit callback  
					error: 		   errorf,
					// other available options: 
					//url:       url         // override for form's 'action' attribute 
					//type:      type        // 'get' or 'post', override for form's 'method' attribute 
					//dataType:  null        // 'xml', 'script', or 'json' (expected server response type) 
					//clearForm: true        // clear all form fields after successful submit 
					//resetForm: true        // reset the form after successful submit
					// $.ajax options can be used here too, for example: 
					//timeout:   3000 
				}; 
                $('#loginForm').ajaxForm(options);
			});
			
			function errorf(){
				alert('error');
			}
					
			//var user = $('#loginForm :text').fieldValue()[0];
			//var dataPara = $('#loginForm').formSerialize()
				
			function complete(data){
				if (data.status==1)
				{
					//$('#result').html(data.info).show();
					// 跳转
					location.href='http://'+data.url;
				}else{
					$('#result').html(data.info).show();
					flashVerify();
				}
			}

            function flashVerify(){
				var timenow = new Date().getTime();
                document.getElementById("verify").src= "<?php echo U('Public/verify/');?>?"+timenow;
            }
        </script>
        <form id="loginForm" action="<?php echo U('Public/checklogin');?>" method="post"> 
            <h3>gtobal运维系统</h3>
            <div id="result" align="center"></div>
            <label for="userName"><span>用户名：</span><input id="userName" name="username" type="text" /></label>
            <label for="passWord"><span>密码：</span><input id="passWord" name="password" type="password" /></label>
            <label><span>验证码：</span><input name="verify" type="password" style="width:60px;" maxlength="4" size="4"/>&nbsp;<img style="vertical-align:middle" id="verify" src="<?php echo U('Public/verify');?>" onclick="flashVerify()"></img>
            </label>
            <label id="submit"><input name="" type="submit" class="bt" value="确定" />
            </label>
        </form>
        <p id="siteCopyRight">版权所有：www.gtobal.com</p>
    </body>
</html>