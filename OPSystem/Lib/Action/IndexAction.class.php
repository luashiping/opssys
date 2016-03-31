<?php
// 本文档自动生成，仅供测试运行
class IndexAction extends OpsAction
{

    public function index()
    {
        $this->display();
    }
	
    public function logout(){
        unset($_SESSION['LOGIN']);
        $this->redirect('Index/index');
    }

}
?>