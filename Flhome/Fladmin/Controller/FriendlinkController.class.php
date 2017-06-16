<?php
namespace Fladmin\Controller;
use Fladmin\Controller\BaseController;

class FriendlinkController extends BaseController
{
    public function index()
    {
        $this->posts = M("Friendlink")->order('id desc')->select();
        $this->display();
    }
    
    public function doadd()
    {
        if(!empty($_POST["webname"])){$data['webname'] = $_POST["webname"];}else {$data['webname']="";}//链接名称
        if(!empty($_POST["url"])){$data['url'] = $_POST["url"];}else {$data['url']="";}//链接网址
        
		if(M('Friendlink')->data($data)->add())
        {
            $this->success('添加成功！', '/'.FLADMIN.'/Friendlink' , 1);
        }
		else
		{
			$this->error('添加失败！请修改后重新添加', '/'.FLADMIN.'/Friendlink/add' , 3);
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
        $this->row = M('Friendlink')->where("id=$id")->find();
        $this->display();
    }
    
    public function doedit()
    {
        if(!empty($_POST["id"])){$id = $_POST["id"];}else {$id="";exit;}
        if(!empty($_POST["webname"])){$data['webname'] = $_POST["webname"];}else {$data['webname']="";}//链接名称
        if(!empty($_POST["url"])){$data['url'] = $_POST["url"];}else {$data['url']="";}//链接网址
        
		if(M('Friendlink')->where("id=$id")->save($data))
        {
            $this->success('修改成功！', '/'.FLADMIN.'/Friendlink' , 1);
        }
		else
		{
			$this->error('修改失败！', '/'.FLADMIN.'/Friendlink/edit?id='.$_POST["id"] , 3);
		}
    }
    
    public function del()
    {
		if(!empty($_GET["id"])){$id = $_GET["id"];}else{$this->error('删除失败！请重新提交','/'.FLADMIN.'/Friendlink' , 3);}if(preg_match('/[0-9]*/',$id)){}else{exit;}
		
		if(M("Friendlink")->where("id=$id")->delete())
        {
            $this->success('删除成功', '/'.FLADMIN.'/Friendlink' , 1);
        }
		else
		{
			$this->error('删除失败！请重新提交', '/'.FLADMIN.'/Friendlink', 3);
		}
    }
}