<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class AdminAction extends Action{
    function __construct() {

        parent::__construct();
        if ($_SESSION['LOGIN'] != '1') {
            $this->redirect('/Public/login');
        }
    }
	
    public function userlist(){
        $admin=M('admin');
        $user=$admin->select();
        $this->assign('users',$user);
        $this->display();
    }
	
    public function useredit(){
		if(empty($_GET['id'])) {
			$this->display();
		} else {
			$admin = M('admin');
			$this->assign('user', $admin->where('Id=' . $_GET['id'])->find());
			$this->display();
		}
    }
	
	public function usersave() {
		if (empty($_POST['Role'])) {
			$this->error('<font color="red">角色不能为空</font>');
		}
		if (empty($_POST['Username'])) {
			$this->error('<font color="red">用户名不能为空</font>');
		}
		if (empty($_POST['Password'])) {
			$this->error('<font color="red">密码不能为空</font>');
		}
		if (empty($_POST['cPassword'])) {
			$this->error('<font color="red">请再次输入密码</font>');
		}
		
		if($_POST['Password'] != $_POST['cPassword']) {
			$this->error('<font color="red">两次输入的密码不一致</font>');
		}
		
		$data['Role'] =  $_POST['Role'];
		$data['Username'] =  $_POST['Username'];
		$data['Password'] =  md5($_POST['Password']);
		
		$admin = M('admin');
		//$admin->create();
		if(empty($_POST['Id'])) {
			if($admin->add($data)) {
				$this->ajaxReturn(array('url' => U("Admin/userlist"),'info'=>'<font color="blue">添加成功</font>','status'=>1),'JSON');
			} else {
				$this->error('<font color="red">添加失败</font>');
			}
		} else {
			if($admin->where('Id=' . $_POST['Id'])->save($data)) {
				$this->ajaxReturn(array('url' => U("Admin/userlist"),'info'=>'<font color="blue">修改成功</font>','status'=>1),'JSON');
			} else {
				$this->error('<font color="red">修改失败</font>');
			}
		}
	}
	
	public function userdel() {
		$admin = M('admin');
        if ($admin->where('id=' . $_GET['id'])->delete()) {
            $this->redirect("/Admin/userlist");
		}
	}
	
}
?>
