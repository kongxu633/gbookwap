<?php
namespace Admin\Controller;

class IndexController extends CommonController {
    public function index(){
    	$login = [
    		'uid' => $_SESSION['uid'],
    		'username' => $_SESSION['username']
    	];
    	$this->assign('login',$login);
        $this->display();
    }
    
    public function welcome(){
        $this->assign('admin',$_SESSION['username']);
        $this->display();
    }
    
    public function logout(){
        session_unset();
        session_destroy();
        $this->redirect(MODULE_NAME . '/Login/index');
    }
}