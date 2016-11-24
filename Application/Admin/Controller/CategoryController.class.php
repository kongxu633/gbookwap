<?php
namespace Admin\Controller;
use Common\Org\Util\Category;
class CategoryController extends CommonController {
    public function index(){
        
        $data = M('cate')->order('pid ASC,sort ASC')->select();
        $list = Category::tree($data);
        //p($list);
        $this->assign('list', $list);
        $this->display();
    }
    
    public function addCate(){
        $this->pid = I('pid',0,'intval');
        $this->display();
    }
    
    public function editCate(){
        $id = I('id',0,'intval');
        
        if(!$id){
            $this->error('非法操作',U(MODULE_NAME.'/Category/index'));
        }
        
        $cate = M('cate')->find($id);
        
        if(!$cate){
            $this->error('查询分类失败',U(MODULE_NAME.'/Category/index'));
        }

        $this->assign('cate', $cate);
        $this->display();
    }
    
    public function updateCate(){
        
        $id = I('id',0,'intval');
        
        if(!$id){
            $this->error('非法操作',U(MODULE_NAME.'/Category/index'));
        }
        
        $data = [
            'name' => I('name'),
        ];
        
        $result = M('cate')->where(['id' => $id])->setField($data);
        
        if(false !==$result ){
            $this->success('更新成功',U(MODULE_NAME.'/Category/index'));
        } else {
            $this->error('更新失败');
        }
    }
    
    public function deleteCate(){
        // 还需要判断 分类下是不是含有 未审核的带此分类的数据
        $id = I('id',0,'intval');
    
        if(!$id){
            $this->error('非法操作',U(MODULE_NAME.'/Category/index'));
        }
        
        $cate = M('cate')->where(['id'=>$id])->find();
        
        if($cate['pid'] == 0){
            //$this->error('删除失败,不能删除顶级分类!');
            if(M('cate')->where(['pid'=>$cate['id']])->find()){
                $this->error('删除失败,请先删除子分类!');
            }
        }
        
        $data = M('article')->where(['cid'=>$id])->select();
        
        if($data){
             $this->error('删除失败,该分类下有文章!');
         }

        $result = M('cate')->delete($id);
    
        if(false !== $result ){
            $this->success('删除成功',U(MODULE_NAME.'/Category/index'));
        } else {
            $this->error('删除失败');
        }
    }
    
    public function saveCate(){
    
        if(M('cate')->add($_POST)){
            $this->success('添加成功',U(MODULE_NAME.'/Category/index'));
        } else {
            $this->error('添加失败');
        }
    }

    public function sortCate(){
        //p(I('post.'));
        
        $arr = I('post.');
        $db = M('cate');
        foreach ($arr as $k=>$v){
            $db->where(['id'=>$k])->setField(['sort'=>$v]);
        }
        $this->redirect(MODULE_NAME.'/Category/index');
        
    }
}