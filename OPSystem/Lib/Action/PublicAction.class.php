<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class PublicAction extends Action{
	private $db_connection = array('shuangtao'=>'DB_CONFIG3');
	
    public function login(){
        $this->display();
    }
    public function verify(){
        import("ORG.Util.Image");  
        Image::buildImageVerify(4,5);
    }
    public function checklogin(){
        if(empty($_POST['username'])) {
                $this->error('<font color="red">帐号必须</font>');
        }elseif (empty($_POST['password'])){
                $this->error('<font color="red">密码必须！</font>');
        }elseif (empty($_POST['verify'])){
                $this->error('<font color="red">验证码必须！</font>');
        }
        if (!isset($_SESSION['verify'])){
            $this->error('<font color="red">验证码失效！</font>');
        } elseif ($_SESSION['verify'] != md5($_POST['verify'])) {
            $this->error('<font color="red">验证码错误！</font>');
        }
        $Admin=M('admin');
		$result = $Admin->where("Username='".$_POST['username']."' and "."Password='".md5($_POST['password'])."'")->select();
        if($result && count($result) == 1) {
            $_SESSION['LOGIN']=1;
            $_SESSION['LOGINUSER']=$_POST['username'];
			$_SESSION['LOGINROLE']=$result[0]['Role'];
			$_SESSION['LOGINUSERID']=$result[0]['Id'];
            $this->ajaxReturn(array('url'=>$_SERVER['HTTP_HOST'],'status'=>'1'),'JSON');
        }else{
            unset ($_SESSION['verify']);
            $this->error('<font color="red">验证失败！</font>');
        }
        
    }
	
}
?>
