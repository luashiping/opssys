<?php
class DockerAction extends OpsAction {
    //list container
    public function clist() {
        import("ORG.Util.Page");
        $containers = D('Docker')->lists();
        $ContainerCount = count($containers);
        $Page = new Page($ContainerCount,10);
        $page=$Page->show();
        $list_containers = array_slice($containers,$Page->firstRow,$Page->listRows);
        $this->assign('containers',$list_containers);
        $this->assign('page', $page);
        $this->display();
    }

    //contain detail
    public function detail(){
        if (empty($_GET['id'])) {
            die('输入容器id');
        }
 
        $id = $_GET['id'];
        $info = D('Docker')->info($id);
        //rr($info);
        $container_info['App_Name'] = ltrim($info['Name'],'/');
        $container_info['Node_Name'] = $info['Node']['Name'];
        $container_info['Node_Ip'] = $info['Node']['IP'];
        $memory_tmp = $info['HostConfig']['Memory'];
        $memory = $memory_tmp / 1024 / 1024;
        $container_info['Memory'] = $memory;

        $created = $info['Created'];
        $create_time_tmp = strtotime(substr($created, 0, strpos($created, '.'))) + 28800;
        $create_time = date('Y-m-d H:i:s',$create_time_tmp);
        $container_info['create_time'] = $create_time;
        //rr($info['NetworkSettings']);
        $ports_temp = $info['NetworkSettings']['Ports'];
        $i = 0;
        if($ports_temp) {
            foreach($ports_temp as $key=>$values) {
                $container_info['ports'][$i]['Port'] = $key;
                foreach($values as $v) {
                    $container_info['ports'][$i]['HostIp'] = $v['HostIp'];
                    $container_info['ports'][$i]['HostPort'] = $v['HostPort'];
                }
                $i++;
            }
        }
        //rr($container_info['ports']);
        $this->assign('container_info',$container_info);
        $this->display();
        
        
    }
    
    //start container
    public function start() {
        if(empty($_GET["id"])) {
            return false;
        } else {
            $id = $_GET["id"];
            if(D('Docker')->start($id)) {
                $this->ajaxReturn(array('info'=>'启动失败','status'=>0));
            } else {
                $this->ajaxReturn(array('info'=>'启动成功','status'=>1));
            }
        }
        
    }
    
    //stop container
    public function stop() {
        if(empty($_GET["id"])) {
            return false;
        } else {
            $id = $_GET["id"];
            if(D('Docker')->stop($id)) {
                $this->ajaxReturn(array('info'=>'停止失败','status'=>0));
            } else {
                $this->ajaxReturn(array('info'=>'停止成功','status'=>1));
            }
        }
        
    }
    
    //del container
    public function del() {
        if(empty($_GET["checkbox"])) {
            if(empty($_GET["id"])) {
                return false;
            } else {
                $ids[] = $_GET["id"];     
            } 
        } else {
            $ids = $_GET["checkbox"];

        }

        if(D('Docker')->remove($ids)) {
            $this->ajaxReturn(array('info'=>'删除失败','status'=>0));
        } else {
            $this->ajaxReturn(array('info'=>'删除成功','status'=>1));
        }
    }

    //
    public function reposlist() {
        import("ORG.Util.Page");
        $repository = D('Registry')->getList();
        $images = $repository['repositories'];
        $ImageCount = count($images);
        $Page = new Page($ImageCount,10);
        $page=$Page->show();
        $list_images = array_slice($images,$Page->firstRow,$Page->listRows);
        $this->assign('repositories',$list_images);
        $this->assign('page', $page);
        $this->display();
    
        
    }
    
    //
    public function cc() {
        $id = D('Docker')->FetchImageid('gtobal-app','12127f9ec056cf7b09c990e6b29ce008a3d11405');
        rr($id);
    }
}

?>
