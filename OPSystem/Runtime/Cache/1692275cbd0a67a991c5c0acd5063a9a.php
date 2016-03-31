<?php if (!defined('THINK_PATH')) exit();?><html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta content="text/html; charset=utf8" http-equiv="Content-Type">
        <title>构建列表</title>
        <link type="text/css" rel="stylesheet" href="__TMPL__Public/style/style.css"></link>
        <script type="text/javascript" src="__TMPL__/Public/Js/Jquery/jquery.js"></script>
        <script type="text/javascript" src="__TMPL__/Public/Js/msgprocess.js"></script>
    </head>
    <body id="page">
        <script>
            function start(id) {
                if(confirm("确定此操作吗？")) {
                    $.get("<?php echo U('Docker/start');?>",
                    {
                        id:id
                    },
                    function(data) {
                        if(data.status==1){
                            alert(data.info);
                            //$('#result').html(data.info).show();
                        }else{
                            alert('操作失败');
                        }
                    });
              }
            }
            
            function stop(id) {
                if(confirm("确定此操作吗？")) {
                    $.get("<?php echo U('Docker/stop');?>",
                    {
                        id:id
                    },
                    function(data) {
                        if(data.status==1){
                            alert(data.info);
                            //$('#result').html(data.info).show();
                        }else{
                            alert('操作失败');
                        }
                    });
                 }
            }
            

           function del(id,obj) {
                if(confirm("确定此操作吗？")) {
                    $.get("<?php echo U('Docker/del');?>",
                    {
                        id:id
                    },
                    function(data) {
                        if(data.status==1){
                            obj.parents('tr').remove();
                            //alert(data.info);
                            //$('#result').html(data.info).show();
                        }else{
                            alert('操作失败');
                        }
                    });
                 }
            }
            function dels() {
                var chk_value =[]; 
                $('input[name="cid"]:checked').each(function(){ 
                    chk_value.push($(this).val()); 
                });

                if (chk_value != "") {
                    $.get("<?php echo U('Docker/del');?>",
                    {
                        checkbox:chk_value
                    },
                    function(data) {
                        if(data.status==1){
                            //obj.parents('tr').remove();
                            $("input[name='cid']:checked").parents('tr').remove();
                        }else{
                            alert('操作失败');
                        }
                    });
                } else {
                    alert("请选择要删除的数据！");
                }
            }

            function CheckAll(form){
                var ifcheck = null;
                for (var i=0;i<form.elements.length;i++){
                    var e = form.elements[i];
                    if(e.type=='checkbox'){
                        if (ifcheck === null) ifcheck = !e.checked;
                        e.checked = ifcheck;
                        if (typeof e.onclick == 'function') e.onclick();
                    }
                }
                return ifcheck;
            }

        </script>
        <style type="text/css">
      
        </style>
        
        <form id="listForm" name="listForm" method="post" action="">
            <h2>
                <a onclick="CheckAll(document.listForm)" href="javascript:void(0);">全选</a>
                <a onclick="if(confirm('确认要删除所选吗？')) {dels()};" href="javascript:void(0);">批量彻底删除</a>
            </h2>
    		<table width="100%" cellspacing="0" cellpadding="0" border="0">
                <tr><td colspan="9"><div id="result" align="center"></div></td></tr>
                <tr>
                    <th width="5%"></th>
                    <th width="20%">name</th>
                    <th width="40%">image</th>
                    <th width="15%">status</th>
    				<th width="20%">action</th>
                
                </tr>
                <?php if(is_array($containers)): $i = 0; $__LIST__ = $containers;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$container): $mod = ($i % 2 );++$i;?><tr>
                        <td><input name="cid" type="checkbox" value="<?php echo ($container["Id"]); ?>" /></td>
                        <td><a href="<?php echo U('Docker/detail',array('id'=>$container['Id']));?>"><?php echo ($container["Names"]["0"]); ?></a></td>
    					<td><?php echo ($container["Image"]); ?></td>
                        <td>
                            <?php if($container["Zt"] == 1): ?><span class="label label-success">运行中</span>
                            <?php else: ?>
                                <span class="label label-danger">已停止</span><?php endif; ?>
                        </td>
                        <td>
                            <?php if($container["Zt"] == 1): ?><a class="btn default disabled">启动</a>
                                <a class="btn default" onclick="stop('<?php echo ($container["Id"]); ?>')">停止</a>
                            <?php else: ?>
                                <a class="btn default" onclick="start('<?php echo ($container["Id"]); ?>')">启动</a>
                                <a class="btn default disabled">停止</a><?php endif; ?>
                            <a class="btn default" onclick="del('<?php echo ($container["Id"]); ?>',$(this))">删除</a>
                       </td>
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                <tr><th colspan="9"><?php echo ($page); ?></th></tr>
            </table>
        </form>
    </body>
</html>