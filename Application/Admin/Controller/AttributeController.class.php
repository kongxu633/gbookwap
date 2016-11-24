<?php
namespace Admin\Controller;

class AttributeController extends CommonController {
    public function index(){
        $attr = M('attr')->select();
        $this->attr = $attr;
        $this->display();
    }
    
    public function addAttr(){
        $this->display();
    }
    
    public function saveAttr(){
        //p(I('post.'));
        if(M('attr')->add($_POST)){
            $this->success('添加成功',U(MODULE_NAME.'/Attribute/index'));
        } else {
            $this->error('添加失败');
        }
    }    
}