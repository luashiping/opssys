<?php if (!defined('THINK_PATH')) exit();?><html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta content="text/html; charset=utf8" http-equiv="Content-Type">
        <title>浏览镜像</title>
        <link type="text/css" rel="stylesheet" href="__TMPL__Public/style/style.css"></link>
        <script type="text/javascript" src="__TMPL__/Public/Js/Jquery/jquery.js"></script>
    </head>
    <body id="page">
        
        <style type="text/css">
      
        </style>
        
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
            <tr><td colspan="9"><div id="result" align="center"></div></td></tr>
            <tr>
                <th width="50%">IMAGE</th>
                <th width="50%">ACTION</th>
               
            
            </tr>
            <?php if(is_array($repositories)): $i = 0; $__LIST__ = $repositories;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$repo): $mod = ($i % 2 );++$i;?><tr>
                    <td><?php echo ($repo); ?></td>
                    <td>创建服务</td>
				
                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
            <tr><th colspan="9"><?php echo ($page); ?></th></tr>
        </table>
    </body>
</html>