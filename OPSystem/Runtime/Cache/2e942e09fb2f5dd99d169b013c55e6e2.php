<?php if (!defined('THINK_PATH')) exit();?>
    <head>
        <meta content="text/html; charset=utf8" http-equiv="Content-Type">
        <title>容器列表</title>
        <link href="__TMPL__Public/style/style.css" rel="stylesheet" type="text/css"></link>
        <script type="text/javascript" src="__TMPL__/Public/Js/Jquery/jquery.js"></script>
        
    </head>
    <body id="page">
    <div>
        <h1 class="tops">主机详情</h1>
    </div>
    <div>
        <ui>
            <li>
                <span style="display:inline-block">App name:</span>
                <span><?php echo ($container_info["App_Name"]); ?></span>
            </li>
            <li>
                <span style="display:inline-block">Node:</span>
                <span><?php echo ($container_info["Node_Name"]); ?></span>
            </li>
            <li>
                <span style="display:inline-block">Node ip:</span>
                <span><?php echo ($container_info["Node_Ip"]); ?></span>
            </li>
            <li>
                <span style="display:inline-block">创建时间:</span>
                <span><?php echo ($container_info["create_time"]); ?></span>
            </li>
            <!--li>
                <span style="display:inline-block">Cpu:</span>
                <span></span>
            </li>-->
            <li>
                <span style="display:inline-block">Memory:</span>
                <span><?php echo ($container_info["Memory"]); ?>MB</span>
            </li>
            
            <li>
                <span style="display:inline-block">服务地址:</span>
                <?php if(empty($container_info["ports"])): ?>null<?php else: ?>
                    <?php if(is_array($container_info["ports"])): $i = 0; $__LIST__ = $container_info["ports"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$pinfo): $mod = ($i % 2 );++$i;?><span><?php echo ($pinfo["HostPort"]); ?>-><?php echo ($pinfo["Port"]); ?></span>&nbsp&nbsp<?php endforeach; endif; else: echo "" ;endif; endif; ?>
            </li>  
        </ui>
    </div>
    </body>