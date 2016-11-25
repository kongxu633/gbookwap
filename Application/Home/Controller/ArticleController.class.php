<?php
namespace Home\Controller;
use Think\Controller;
class ArticleController extends Controller {

    public function add(){
        $this->display();
    }

    public function save(){

        $content = I('content');
        $pics = I('pic');

        if(empty($content)){

            $this->error('留言内容为空!');
        }

        $title = mb_substr($content , 0 , 60);
        $list = M('article')->where( ['title' => $title] )->find();
        if($list){
            $this->error('发布过相似内容的留言!');
        }

        $ip = get_ip();
        $area = get_location($ip);

        $data = [
            'title' => $title,
            'content' => $content,
            'time' => time(),
            'click' => mt_rand(10,200),
            'cid' => 0,
            'del' =>1,
            'ip' => $ip,
            'area' => $area,
        ];

        $aid = M('article')->data($data)->add();

        if($aid === false){
            $this->error('留言保存失败！');
        }

        if(!empty($pics)){
            foreach ($pics as $v) {
                $arr['path'] = parse_pic($v);
                $arr['name'] = parse_pic($v,true);
                $arr['aid'] = $aid;
                M('pic')->data($arr)->add();
            }
        }

        $this->display();
    }

    public function upload(){

        $file_string = I('post.base64_string','','base64_decode');

        $savename = date("Ym").'/'.date("d").'_'.uniqid().'.jpg';//localResizeIMG压缩后的图片都是jpeg格式
        $file_path = './Uploads/' . $savename;

        $file = new \Think\Storage\Driver\File;
        $info = $file->put($file_path,$file_string);

        if($info){
            $result['status'] = 1;
            $result['content'] = "上传成功";
            $result['url'] = $savename;
        } else {
            $result['status'] = 0;
            $result['content'] = "上传失败";
        }

        $this->ajaxReturn($result);

    }

    public function gotoweb(){
        echo '<meta http-equiv="refresh" content="0;url=http://nt.jfbst.com">';
    }
}
