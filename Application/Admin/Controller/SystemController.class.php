<?php
namespace Admin\Controller;
use Think\Controller;
class SystemController extends CommonController {
    public function verify(){
        $this->display();
    }
    
    public function updateVerify(){

        if(F313('verify',$_POST,CONF_PATH)){
            $this->success('修改成功',U(MODULE_NAME . '/System/verify'));
        } else {
            $this->error('修改失败，'.CONF_PATH.'verify.php权限');
        }
    }
    
    
}