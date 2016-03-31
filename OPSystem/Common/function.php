<?php
/*
应用程序公共函数
*/

//代码发布日志记录
function logrecord($data = array()) {
	$code_log = M('code_log');
	$result = $code_log->add($data);
	if($result && is_int($result)) {
		return true;
	} else {
		return false;
	}
}

//
function rr($content) {
    die($content);
   
    
}

?>