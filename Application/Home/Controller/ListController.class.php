<?php
namespace Home\Controller;
use Think\Controller;
class ListController extends Controller {
    public function index(){
        $cate = M('cate')->where('pid > 0')->select();
        
        foreach ($cate as $k=>$v){
            $where = ['cid'=>$v['id']];
            $cate[$k]['num'] = M('article')->where($where)->count();
        }

        $this->assign('cate',$cate)->display();
    }
}