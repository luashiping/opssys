<?php
/*
Ӧ�ó��򹫹�����
*/

//���뷢����־��¼
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