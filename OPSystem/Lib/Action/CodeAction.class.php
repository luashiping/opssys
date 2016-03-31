<?php
class CodeAction extends Action{
    /*
    881de7363e7e167839f16d61450a26ea
    docker run -rm -v cluster:/tmp/cluster swarm list file:///tmp/cluster
    docker run -d -p 8888:2375 -v /root/cluster:/tmp/cluster swarm manage file:///tmp/cluster
	function __construct() {
        parent::__construct();
        if ($_SESSION['LOGIN'] != '1') {
            $this->redirect('/Public/login');
        }
    }
    */
    
	//Get a list of repository commits in a project
    public function commits() {
        $client = new \Gitlab\Client('http://gitlab.example.com/api/v3/'); // change here
        $client->authenticate('WaCetppbyFZFxzJf7Ujo', \Gitlab\Client::AUTH_URL_TOKEN);
        $commits = $client->api('repositories')->commits(1,0,$per_page = 10);
        return $commits;
	}

    //test
    public function dd() {
        $oClient = new \GitlabCI\Client('http://ci.example.com/api/v1/projects');
        $oClient->authenticate('8f9475a3f4b6643060080ebd893adf','http://gitlab.example.com', \GitlabCI\Client::AUTH_URL_TOKEN);
        $oClient->api('builds')->register('0c4ed9dc3c29e8b30abe8e8e302164');
        //$oProject = $oClient->api('builds')->update(3);
        //rr($oProject);
        //$oProject = $oClient->api('projects')->create('My Project', array(
        //    'gitlab_id' => 20,
        //));
    }
	
	//Get build list
	public function buildlist() {
        import("ORG.Util.Page");
        $order = 'build_id';
        $commits = $this->commits();
        $this->assign('commits', $commits);
        $Builds = M('builds_log');
        $BuildCount = $Builds->count();
		$Page = new Page($BuildCount,15);
        $page=$Page->show();
        $this->assign('builds',$Builds->order($order." desc")->limit($Page->firstRow.','.$Page->listRows)->select());
        $this->assign('page', $page);
        $this->display();
	}
	
	//CI web hooks
	public function trigger_ci() {
        $json = file_get_contents('php://input');
        $data = json_decode($json,true);
        $f = fopen("/data/www/web/2.log","r+");
        fwrite($f,$json);
        $Builds = M('builds_log');
        $start_time = substr($data['build_started_at'],0,-6);
        $end_time = substr($data['build_finished_at'],0,-6);
        $duration = strtotime($end_time) - strtotime($start_time);
        $build_data['build_id'] = $current_data['build_id'] = $data['build_id'];
        $build_data['build_status'] = $data['build_status'];
        $build_data['commit'] = $current_data['commit'] = $data['sha'];
        $build_data['ref'] = $current_data['ref'] = $data['ref'];
        $build_data['build_started_at'] = $start_time;
        $build_data['duration'] = $duration.'秒';
        $build_data['author'] = $data['push_data']['user_name'];  
        $build_data['image_id'] = $current_data['image_id'] = D('Docker')->FetchImageid('gtobal-app',$data['sha']);//获取image id
        $Builds->add($build_data);
        $current_data['id'] = 1;
        //更改当前运行容器
        if($data['build_status'] == 'success'){
            $current = M('current_status');
            $current->add($current_data,$options=array(),$replace=true);
        }
	}
    
	//Push web hooks
	public function trigger_push(){
		$json = file_get_contents('php://input');
        $data = json_decode($json,true);
        file_put_contents('1.log',$json);
	}
	
    //get current status
    public function status() {
        $current = M('current_status');
        $this->assign('current_status',$current->select());
        $this->display();
    }
    
    //publish code to online testing machine
    public function deploy(){
        if(empty($_GET['image_id'])) {
            return false;
        } 
        exec('python '.SCRIPT_PATH.'deploy.py',$output,$return_var);
        if($output[0] == 1) {
            $current = M('current_status');
            $current_data['publish_status'] = 1;
            if($current->where('id=1')->save($current_data)) {
                $this->ajaxReturn(array('info'=>'<span style="color:green;">已部署</span>','status'=>1));
            } else {
                $this->ajaxReturn(array('info'=>'update failed','status'=>0));
            }
		} else {
			$this->error('scrpit failed');
		}	
    }
    
    //publish code to production environment
    public function online() {
        exec('python '.SCRIPT_PATH.'online.py',$output,$return_var);
        if($return_var == 1) {
            $current = M('current_status');
            $current_data['publish_status'] = 2;
            if($current->where('id=1')->save($current_data)) {
                $this->ajaxReturn(array('info'=>'<span style="color:blue;">已上线</span>','status'=>1));
            } else {
                $this->ajaxReturn(array('info'=>'update failed','status'=>0));
            }
        } else {
            $this->error('scrpit failed');
        }
    }
    
	//爽淘发布
	public function srelease() {
		exec('python '.SCRIPT_PATH.'release.py',$output,$return_var);
		if($output[0] == 1) {
			echo '发布成功';
		} else {
			echo '发布失败';
		}
	}

    
}
?>
