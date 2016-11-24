<?php
namespace Admin\Controller;
use Think\Controller;
class CommonController extends Controller {

    /* public function __construct(){
        parent::__construct();
        echo 1;
    } */    
    // thinkphp 内置钩子 和上面效果一样
    public function _initialize(){
        //echo 'common';
        if(!isset($_SESSION['uid'])){
            $this->redirect(MODULE_NAME . '/Login/index');
        }
    }

}