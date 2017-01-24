<?php
namespace Home\Model;
use Think\Model\RelationModel;

class ArticleModel extends RelationModel{

    //主表名称 好像没用
    //protected $tableName = 'article';

    //定义关联关系
    protected $_link = array(

        'attr' => array(
            'mapping_type' => self::MANY_TO_MANY,
            'foreign_key' => 'art_id',
            'relation_foreign_key' => 'attr_id',
            'relation_table' => 'le_article_attr' //此处应显式定义中间表名称，且不能使用C函数读取表前缀
        ),

        'cate' => array(
            'mapping_type' => self::BELONGS_TO,
            'foreign_key' => 'cid',
            'mapping_fields' => 'name',
            'as_fields' => 'name:cate'
        ),

        'pic' => array(
            'mapping_type' => self::HAS_MANY,
            'foreign_key' => 'aid',
            'mapping_fields' => 'name,path',
            'as_fields' => 'name:pic'
        ),

    );


    public function getTopArticle($del = 0,$from = 0,$num = 10){
        $where = [['aid' => 1],['del' => $del]];
        return $this->where($where)->relation(true)->order('time DESC')->limit($from,$num)->select();
    }

    public function getArticle($del = 0,$from = 0,$num = 100){
        $where = ['del' => $del];
        return $this->where($where)->relation(true)->order('aid DESC,time DESC')->limit($from,$num)->select();
    }

    public function getCateArticle($cid = 0){
        $where = ['cid' => $cid , 'del' => 0];
        return $this->where($where)->relation(true)->order('time DESC')->select();
    }

}