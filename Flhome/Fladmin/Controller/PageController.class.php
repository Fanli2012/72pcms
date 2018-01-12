<?php
namespace Fladmin\Controller;
use Fladmin\Controller\BaseController;

class PageController extends BaseController
{
    public function index()
    {
        $this->posts = M("Page")->order('id desc')->select();
        $this->display();
    }
    
    public function doadd()
    {
        if(!empty($_POST["template"])){$data['template'] = $_POST["template"];}else {$data['template']="";}//作者
        if(!empty($_POST["filename"])){$data['filename'] = $_POST["filename"];}else {$data['filename']="";}//来源
        if(!empty($_POST["litpic"])){$data['litpic'] = $_POST["litpic"];}else {$data['litpic']="";}
        if(!empty($_POST["title"])){$data['title'] = $_POST["title"];}else {$data['title']="";}
        if(!empty($_POST["seotitle"])){$data['seotitle'] = $_POST["seotitle"];}else {$data['seotitle']="";}
        if(!empty($_POST["keywords"])){$data['keywords'] = $_POST["keywords"];}else {$data['keywords']="";}
        if(!empty($_POST["description"])){$data['description'] = $_POST["description"];}else {$data['description']="";}
        if(!empty($_POST["content"])){$data['body'] = $_POST["content"];}else {$data['body']="";}
        $data['pubdate'] = time();//更新时间
        $data['click'] = rand(200,500);//点击
        
        $Page = M('Page');
        
        if($Page->data($data)->add())
        {
            $this->success('添加成功！', U('Page/index'), 1);
        }
		else
		{
			$this->error('添加失败！请修改后重新添加', U('Page/add'), 3);
		}
    }
    
    public function add()
    {
        $this->display();
    }
    
    public function edit()
    {
        if(!empty($_GET["id"])){$id = $_GET["id"];}else{$id="";}
        if(preg_match('/[0-9]*/',$id)){}else{exit;}
        
        $this->id = $id;
        $this->row = M('Page')->where("id=$id")->find();
        $this->display();
    }
    
    public function doedit()
    {
        if(!empty($_POST["id"])){$id = $_POST["id"];}else {$id="";exit;}
        if(!empty($_POST["template"])){$data['template'] = $_POST["template"];}else {$data['template']="";}//作者
        if(!empty($_POST["filename"])){$data['filename'] = $_POST["filename"];}else {$data['filename']="";}//来源
        if(!empty($_POST["litpic"])){$data['litpic'] = $_POST["litpic"];}else {$data['litpic']="";}
        if(!empty($_POST["title"])){$data['title'] = $_POST["title"];}else {$data['title']="";}
        if(!empty($_POST["seotitle"])){$data['seotitle'] = $_POST["seotitle"];}else {$data['seotitle']="";}
        if(!empty($_POST["keywords"])){$data['keywords'] = $_POST["keywords"];}else {$data['keywords']="";}
        if(!empty($_POST["description"])){$data['description'] = $_POST["description"];}else {$data['description']="";}
        if(!empty($_POST["content"])){$data['body'] = $_POST["content"];}else {$data['body']="";}
        $data['pubdate'] = time();//更新时间
        
        $Page = M('Page');
        
        if($Page->where("id=$id")->save($data))
        {
            $this->success('修改成功！', U('Page/index'), 1);
        }
		else
		{
			$this->error('修改失败！请修改后重新添加', U('Page/edit',array('id'=>$_POST["id"])), 3);
		}
    }
    
    public function del()
    {
		if(!empty($_GET["id"])){$id = $_GET["id"];}else{$this->error('删除失败！请重新提交', U('Page/index'), 3);}if(preg_match('/[0-9]*/',$id)){}else{exit;}
		
		if(M("Page")->where("id=$id")->delete())
        {
            $this->success('删除成功', U('Page/index'), 1);
        }
		else
		{
			$this->error('删除失败！请重新提交', U('Page/index'), 3);
		}
    }
}
