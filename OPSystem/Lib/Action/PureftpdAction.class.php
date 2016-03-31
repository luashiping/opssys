<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class PureftpdAction extends OpsAction {
	private $db_connection = array('204'=>'DB_CONFIG2','14'=>'DB_CONFIG1','233'=>'DB_CONFIG4');
								
	private $db_server = array('204','14','233');
	private $ftp_servers = array('204'=>'115.238.28.101','14'=>'115.238.28.99','233'=>'192.168.2.233');
							
    
	//ftp用户列表页
    public function userlist() {
		$condition = array();
		$condition['enable'] = 1;
        if (!empty($_REQUEST['order'])){
            if($_REQUEST['order']=='ip'){
                $order='ip';
            }elseif($_REQUEST['order']=='username'){
                $order='User';
            }elseif($_REQUEST['order']=='uid'){
                $order='Uid';
            }elseif($_REQUEST['order']=='gid'){
                $order='Gid';
            }else{
                $order='Id';
            }
        }else{
            $order='Id';
        }
		
		if(!empty($_POST['keyword']) && !empty($_POST['at'])) {
			if($_POST['at'] =='ip' || $_POST['at']=='note') {
				$condition[$_POST['at']] = array('like',"%".$_POST['keyword']."%");
			}
		}
		
		if(!empty($_REQUEST['dbserver'])) {
			$db_config = $this->db_connection[$_REQUEST['dbserver']];
		} else {
			return false; //之后改404;
		}
		
        import("ORG.Util.Page");
		$User = M('users','',$db_config);
        $userCount=$User->where($condition)->count();
        $Page=new Page($userCount,15);
        $page=$Page->show();
		//var_dump($User->join('right join server on ftp_users.ip = server.ip')->where("enable='1'")->order($order)->limit($Page->firstRow.','.$Page->listRows)->select(array('field'=>'ftp_users.*,server.ftp_ip')));
		//exit;
        $this->assign('users', $User->where($condition)->order($order)->limit($Page->firstRow.','.$Page->listRows)->select());
        $this->assign('page', $page);
		$this->assign('dbserver',$_REQUEST['dbserver']);
        $this->display();
    }

    public function useredit() {
        $Server = M('server');
        $this->assign('servers', $Server->select());
		if(!empty($_GET['dbserver'])) {	
			if (!empty($_GET['id'])) {
				$db_config = $this->db_connection[$_GET['dbserver']];
				$User = M('users','',$db_config);
				$users = $User->where('Id=' . $_GET['id'])->find();
			}
			$users['dbserver'] = $_GET['dbserver'];
			$this->assign('user', $users);
			$this->display();
        } else {
			return false;
		}
    }

    public function userdel() {
		if(empty($_GET['dbserver']) || empty($_GET['id'])) {
			return false;
		} else {
			$db_config = $this->db_connection[$_GET['dbserver']];
		}
		$User = M('users','',$db_config);
		if($User->where('Id='.$_GET['id'])->delete()) {
			$this->ajaxReturn(array('info'=>'<font color="blue">删除成功</font>','status'=>1));
		} else {
			$this->error('<font color="red">删除失败</font>');
		}
		/*
        if($_GET['clearup']==1){
            if ($User->where('id ='.$_GET['id'])->delete()) {
                $this->redirect("/Pureftpd/userdeleted");
            }else {
                $this->redirect("/Pureftpd/userdeleted");
            }
        }elseif($_GET['clearupall']==1){
            if ($User->where("Deleted='1'")->delete()) {
                $this->redirect("/Pureftpd/userdeleted");
            }else {
                $this->redirect("/Pureftpd/userdeleted");
            }
        } else {
            $User->where('id=' . $_GET['id'])->find();
            $User->Deleted='1';
            $User->Status='0';
            if($User->save()){
                //$this->ajaxReturn('<font color="blue">删除成功</font>',TRUE);
				echo json_encode(array('status'=>1));
            }else{
                //$this->error('<font color="red">删除失败</font>',TRUE);
				echo json_encode(array('status'=>0));
            }
        }
		*/
    }
    public function userresume(){
        $User = M('users');
        if($User->where('id ='.$_GET['id'])->find()){
            $User->Status='1';
            $User->Deleted='0';
            $User->save();
        }
        $this->redirect("/Pureftpd/userdeleted");
        
    }
    public function userdeleted(){
        if (!empty($_REQUEST['order'])){
            if($_REQUEST['order']=='ip'){
                $order='At';
            }elseif($_REQUEST['order']=='username'){
                $order='User';
            }elseif($_REQUEST['order']=='uid'){
                $order='Uid';
            }elseif($_REQUEST['order']=='gid'){
                $order='Gid';
            }else{
                $order='Id';
            }
        }else{
            $order='Id';
        }
        import("ORG.Util.Page");
        $User = M('users');
        $userCount=$User->where("Deleted='1'")->count();
        $Page=new Page($userCount,15);
        $page=$Page->show();
        $this->assign('users', $User->where("Deleted='1'")->order($order)->limit($Page->firstRow.','.$Page->listRows)->select());
        $this->assign('page', $page);
        $this->assign('clearup',1);
        $this->display('userlist');
    }

    public function usersave() {
		if (empty($_POST['User'])) {
			$this->error('<font color="red">帐号不能为空</font>');
		}

		if (!empty($_POST['Password'])) {
			if ($_POST['Password'] != $_POST['confirm_Password']) {
				$this->error('<font color="red">两次密码不匹配</font>');
			} else {
				$data['Password'] = md5($_POST['Password']);
			}
		}
		
		if(empty($_POST['dbserver'])) {
			$this->error('<font color="red">内部出错</font>');
		} else {
			$db_config = $this->db_connection[$_POST['dbserver']];
            $User = M('users','',$db_config);
		}
		$data['User'] = $_POST['User'];
		$data['ip'] = $_POST['At'];
		$data['Uid'] = $_POST['Uid'];
		$data['Gid'] = $_POST['Gid'];
		$data['Dir'] = $_POST['Dir'];
		$data['ULBandwidth'] = $_POST['ULBandwidth'];
		$data['DLBandwidth'] = $_POST['DLBandwidth'];
		//$data['QuotaFiles'] = $_POST['QuotaFiles'];
		$data['QuotaSize'] = $_POST['QuotaSize'];
		//$data['ULRatio'] = $_POST['ULRatio'];
		//$data['DLRatio'] = $_POST['DLRatio'];
		//$data['Ipaddress'] = $_POST['Ipaddress'];
		$data['note'] = $_POST['Comment'];
		
        if (empty($_POST['Id'])) {
			if ($User->add($data)) {
				$this->ajaxReturn(array('url' => U("Pureftpd/userlist")), '<font color="blue">添加成功</font>','JSON');
            } else {
                $this->error('<font color="red">添加失败</font>');
			}
		} else {
            if ($User->where('Id ='.$_POST['Id'])->save($data)) {
				$this->ajaxReturn(array('url' => U("Pureftpd/userlist")), '<font color="blue">修改成功</font>','JSON');
            } else {
				$this->error('<font color="red">修改失败</font>');
			}
		}
    }

    public function serverlist() {
		import("ORG.Util.Page");
		$order = 'id';
		$Server = M('server');
        $serverCount=$Server->count();
		$Page=new Page($serverCount,15);
        $page=$Page->show();
        $this->assign('server', $Server->order($order)->limit($Page->firstRow.','.$Page->listRows)->select());
        $this->assign('page', $page);
        //$this->assign('clearup',1);
        $this->display();
    }

    public function getinfo() {
        if (!empty($_POST['ip'])) {
            $timestamp = time();
            $token = md5($timestamp . C('API_SECRET'));
            $u = yaml_parse_url("http://" . $_POST['ip'] . "/pureftpd/ftp.php?t=$timestamp&tk=$token&a=getuid");
            if ($u['status'] == 'OK') {
                $info['u'] = $u['list'];
            } elseif (is_array($u)) {
                $this->error('<font color="red">获取uid失败：'.$u["status"].'</font>');
            } else {
                $this->error('<font color="red">服务器返回异常或连接服务器失败</font>');
            }
            $g = yaml_parse_url("http://" . $_POST['ip'] . "/pureftpd/ftp.php?t=$timestamp&tk=$token&a=getgid");
            if ($g['status'] == 'OK') {
                $info['g'] = $g['list'];
            }elseif (is_array($g)) {
                $this->error('<font color="red">获取gid失败：'.$g["status"].'</font>');
            }else {
                $this->error('<font color="red">服务器返回异常或连接服务器失败</font>');
            }
            $this->ajaxReturn($info);
        } else {
            $this->error('<font color="red">ip信息不正确</font>');
        }
    }

    public function lock() {
		if(empty($_GET['dbserver']) || empty($_GET['id'])) {
			return false;
		} else {
			$db_config = $this->db_connection[$_GET['dbserver']];
		}
		$User = M('users','',$db_config);
		$User->find($_GET['id']);
		if($User->where('Id='.$_GET['id'])->delete()) {
			$this->ajaxReturn(array('info'=>'<font color="blue">删除成功</font>','status'=>1));
		} else {
			$this->error('<font color="red">删除失败</font>');
		}
            $User = M('users');
            $User->find($_GET['id']);
            if ($User->Status == 1) {
                $User->Status = "0";
                if ($User->save()) {
                    //$this->redirect("/Pureftpd/userlist");
                    $this->ajaxReturn(array('status'=>0),'<font color="blue">锁定成功</font>');
                } else {
                    $this->error('<font color="red">锁定失败!</font>');
                }
        }
    }

    public function getdir() {
        if (!empty($_REQUEST['ip'])) {
            if (empty($_REQUEST['path'])) {
                $Server = M('server');
                $Server->where("ip='" . $_REQUEST['ip'] . "'")->find();
                if (!empty($Server->dir)) {
                    $this->assign('basedirs', split(',', $Server->dir));
                    $this->display('dir');
                } else {
                    echo('<font color="red">该服务器可能没有指定导出目录 :' . $u['status'] . '</font>');
                }
            } else {
                $timestamp = time();
                $token = md5($timestamp . C('API_SECRET'));
                $dir = str_replace('|', '/', $_REQUEST['path']);
                $d= yaml_parse_url("http://" . $_REQUEST['ip'] . "/pureftpd/ftp.php?t=$timestamp&tk=$token&a=getdir&p=" . $dir);
                if ($d['status'] == 'OK') {
                    $this->assign('dirs', $d['list']);
                    $this->display('dir');
                } elseif (is_array($d)) {
                    echo('<font color="red">获取目录信息失败 :' . $d['status'] . '</font>');
                } else {
                    echo('<font color="red">服务器返回异常或连接服务器失败</font>');
                }
            }
        } else {
            echo('<font color="red">ip信息不完整</font>');
        }
    }

    public function userimport() {
        if (isset($_POST['puredb']) and isset($_POST['serverip'])) {
            if (empty($_POST['puredb']) or empty($_POST['serverip'])) {
                $this->error('<font color="red">信息输入不完整</font>');
            }
            $userlines = explode("\n", $_POST['puredb']);
            $iCounter = 0;
            for ($iCounter = 0; $iCounter < count($userlines); $iCounter++) {
                $userinfo = explode(':', $userlines[$iCounter]);
                if (count($userinfo) < 18) {
                    continue;
                }
                $user['User'] = $userinfo[0];
                $user['Password'] = $userinfo[1];
                $user['At'] = $_POST['serverip'];
                $user['Uid'] = $userinfo[2];
                $user['Gid'] = $userinfo[3];
                $user['Comment'] = $userinfo[4]."  ".$userinfo[15];
                $user['Dir'] = str_replace('/./', '', $userinfo[5]);
                $user['QuotaFiles'] = empty($userinfo[11]) ? 0 : $userinfo[11];
                $user['QuotaSize'] = empty($userinfo[12]) ? 0 : $userinfo[12] / 1024 / 1024;
                $user['ULBandwidth'] = empty($userinfo[6]) ? 0 : $userinfo[6] / 1024;
                $user['DLBandwidth'] = empty($userinfo[7]) ? 0 : $userinfo[7] / 1024;
                //$user['Ipaddress'] = empty($userinfo[15]) ? '*' : $userinfo[15];
                $user['Ipaddress'] = '*';
                $user['Status'] = '1';
                $user['ULRatio'] = empty($userinfo[8]) ? 0 : $userinfo[8];
                $user['DLRatio'] = empty($userinfo[9]) ? 0 : $userinfo[9];

                $users[] = $user;
            }
            $User = M('users');
            if ($User->addAll($users)) {
                $this->ajaxReturn(array('url' => U('Pureftpd/userlist')), '<font color="blue">导入成功</font>');
            } else {
                echo $this->error('<font color="red">导入失败</font>');
            }
        } else {
            $Server = M('server');
            $server = $Server->select();
            $this->assign('servers', $server);
            $this->display();
        }
    }

    public function serveredit() {
        if (empty($_GET['id'])) {
            $this->display();
        } else {
            $Server = M('server');
            $this->assign('server', $Server->where('id=' . $_GET['id'])->find());
            $this->display();
        }
    }

    public function serversave() {
		//角色IP判断
		if (empty($_POST['role'])) {
                var_dump($this->error('<font color="red">角色不能为空</font>'));exit;
		}
		if (empty($_POST['ip'])) {
                $this->error('<font color="red">IP不能为空</font>');
		}
		
		$Server = M('server');
		$Server->create();
        if (empty($_POST['id'])) {
            if ($Server->add()) {
                $this->ajaxReturn(array('url' => U("Pureftpd/serverlist"),'info'=>'<font color="blue">添加成功</font>','status'=>1));
            } else {
                $this->error('<font color="red">添加失败,请检查表单</font>');
            }
        } else {
            if ($Server->save()) {
                $this->ajaxReturn(array('url' => U('/Pureftpd/serverlist'), 'info'=>'<font color="blue">修改成功</font>','status'=>1));
            } else {
                $this->error('<font color="red">修改失败!</font>');
            }
        }
    }

    public function serverdel() {
        $Server = M('server');
        if ($Server->where('id=' . $_GET['id'])->delete()) {
            $this->redirect("/Pureftpd/serverlist");
        }else {
            $this->redirect("/Pureftpd/userlist");
        }
    }
    public function serveronline(){
        if (!empty($_REQUEST['ip'])) {
            $timestamp = time();
            $token = md5($timestamp . C('API_SECRET'));
            $ol = yaml_parse_url("http://".$_REQUEST['ip']."/pureftpd/ftp.php?t=$timestamp&tk=$token&a=getonline");
            if ($ol['status'] == 'OK') {
                foreach($ol['list'] as $online){
                    $onlines[]=explode('|',$online);
                }
                $this->assign('onlines', $onlines);
                $this->display('online');
            } elseif (is_array($ol)) {
                if($ol['status']=='NOT_ROOT'){
                    $this->assign('error', '<font color="red">您的服备器不允许以非root用户执行pure-ftpwho命令，若您还要使用此功能请在服务器执行：chmod u+s /path/to/pure-ftpwho</font>');
                    $this->display('online');
                }else{
                    $this->assign('error', '<font color="red">获取在线用户信息失败 :' . $ol['status'] . '</font>');
                    $this->display('online');
                }
            } else {
                $this->assign('error', '<font color="red">服务器返回异常或连接服务器失败</font>');
                $this->display('online');
            }
        }else{
            $this->assign('error', '<font color="red">IP信息不正确</font>');
            $this->display('online');
        }
    }
	
	//ftp登录测试
	public function ftpverify() {
		if($_REQUEST['user'] && $_REQUEST['ip'] && $_REQUEST['dbserver']) {
			if(in_array($_REQUEST['dbserver'],$this->db_server)) {
				$ftp_user = $_REQUEST['user'];
				$ftp_ip = $_REQUEST['ip'];
				$ftp_server = $this->ftp_servers[$_REQUEST['dbserver']];
				$ftp_port = "52816";
			} else {
				return false;
			}
		} else {
			return false;
		}

		$conn_id = ftp_connect($ftp_server,$ftp_port) or die("Couldn't connect to $ftp_server");
		var_dump($conn_id);
		exit;
		if (@ftp_login($conn_id, $ftp_user, $ftp_pass)) {
			echo "Connected as $ftp_user@$ftp_server\n";
		} else {
			echo "Couldn't connect as $ftp_user\n";
		}
	}
}

?>
