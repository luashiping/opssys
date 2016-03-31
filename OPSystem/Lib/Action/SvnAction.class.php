<?php
class SvnAction extends Action {
	function __construct() {
        parent::__construct();
        if ($_SESSION['LOGIN'] != '1') {
            $this->redirect('/Public/login');
        }
    }

	//svn用户
	public function userlist() {
		exec('C:\Python27\python.exe '.C('script_path').'\svn\user.py',$output,$return_var);
		$userlist = str_replace(' ','',substr($output[0],4,-5));
		$users_array = explode(',',$userlist);
		foreach($users_array as $value) {
			$User[]['User'] = trim($value,"'");
		}
		import("ORG.Util.Page");
		$userCount = count($User);
		$Page=new Page($userCount,15);
        $page=$Page->show();
		$User = array_slice($User,$Page->firstRow,$Page->listRows);
		$this->assign('page', $page);
		$this->assign('users',$User);
		$this->display();
	}
	
	public function useredit() {
		$User = array();
		if(!empty($_GET['user'])) {
			$User['User'] = $_GET['user'];
		}
		$this->assign('user',$User);
		$this->display();	
	}
}

	





?>