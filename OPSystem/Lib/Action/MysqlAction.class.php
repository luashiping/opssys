<?php

class MysqlAction extends Action {
    public function mysqlpma () {
        $timestamp = time();
        $token = md5($timestamp . C('API_SECRET'));
        $this->assign('parem','?t='.$timestamp.'&tk='.$token);
        $this->display();
    }
}
?>
