<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        $cache_art = 'index_index_art';
        if(S($cache_art)){
            $art = S($cache_art);
        }else{
            $art = D('article')->getArticle(0,0,200);
            S($cache_art,$art,180);
        }
        $this->assign('art',$art);
        $this->display();
    }

    public function getArticle(){
        $cid = I('cid',0,'intval');

        $cache_art = 'index_getarticle_art_' . $cid;
        $cache_cate = 'index_getarticle_cate_' . $cid;

        if($cid === 0) {
            $cate['name'] = '无分类';
        } else {

            if(S($cache_cate)){
                $cate = S($cache_cate);
            }else
            {
                $cate = M('cate')->where(array('id'=>$cid))->find();
                S($cache_cate,$cate,1800);
            }
        }
        $this->assign('cate',$cate);

        if(S($cache_art)){
            $art = S($cache_art);
        }else
        {
            $art = D('article')->getCateArticle($cid);
            S($cache_art,$art,1800);
        }
        $this->assign('art',$art);


        $this->display();
    }
}
