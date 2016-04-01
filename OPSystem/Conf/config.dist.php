<?php
return array(
    'SESSION_OPTIONS'       => array('path'=>"tcp://memcached-1:11211"),
    //'配置项'=>'配置值'
    'URL_MODEL'=>2, // 如果你的环境不支持PATHINFO 请设置为3
    'DB_TYPE'=>'mysql',
    'DB_HOST'=>'',
    'DB_NAME'=>'',
    'DB_USER'=>'',
    'DB_PWD'=>'',
    'DB_PORT'=>'3306',
    'DB_PREFIX'=>'',
						
	'script_path'=>APP_PATH.'Script',
    
    //docker swarm
	'SWARM_CONFIG' => array(
        'swarm_host' =>'',//公司内部docker
        'swarm_port' =>'',
     
     ),
     
     //register
     'REGISTRY_CONFIG' => array(
        'host' =>'',
        'port' =>'',
     ),
     
	
);
?>
