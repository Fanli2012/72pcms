<?php
namespace Fladmin\Controller;
use Fladmin\Controller\BaseController;

class KeywordController extends BaseController
{
    public function index()
    {
		$counts = M("Keyword")->field('id')->count();
		
		$pagesize =50;$page =0;
        if($counts % $pagesize){ //取总数据量除以每页数的余数
        $pages = intval($counts/$pagesize) + 1; //如果有余数，则页数等于总数据量除以每页数的结果取整再加一,如果没有余数，则页数等于总数据量除以每页数的结果
        }else{$pages = $counts/$pagesize;}
        if(!empty($_GET["page"])){$page = $_GET["page"]-1;$nextpage=$_GET["page"]+1;$previouspage=$_GET["page"]-1;}else{$page = 0;$nextpage=2;$previouspage=0;}
        if($counts>0){if($page>$pages-1){exit;}}
        $start = $page*$pagesize;
		$this->pages = $pages;
        $this->page = $page;
        $this->nextpage = $nextpage;
        $this->previouspage = $previouspage;
		
        $this->posts = M("Keyword")->order('id desc')->limit($start,$pagesize)->select();
        $this->display();
    }
    
    public function doadd()
    {
        if(!empty($_POST["keyword"])){$data['keyword'] = $_POST["keyword"];}else {$data['keyword']="";}//关键词名称
        if(!empty($_POST["rpurl"])){$data['rpurl'] = $_POST["rpurl"];}else {$data['rpurl']="";}//链接网址
        
        if(M('Keyword')->data($data)->add())
        {
            S('keywordlist',null); // 删除缓存
            $this->success('添加成功！', '/'.FLADMIN.'/Keyword' , 1);
        }
		else
		{
			$this->error('添加失败！请修改后重新添加', '/'.FLADMIN.'/Keyword/add' , 3);
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
        $this->row = M('Keyword')->where("id=$id")->find();
        $this->display();
    }
    
    public function doedit()
    {
        if(!empty($_POST["id"])){$id = $_POST["id"];}else {$id="";exit;}
        if(!empty($_POST["keyword"])){$data['keyword'] = $_POST["keyword"];}else {$data['keyword']="";}//关键词名称
        if(!empty($_POST["rpurl"])){$data['rpurl'] = $_POST["rpurl"];}else {$data['rpurl']="";}//链接网址
        
		if(M('Keyword')->where("id=$id")->save($data))
        {
            S('keywordlist',null); // 删除缓存
            $this->success('修改成功！', '/'.FLADMIN.'/Keyword' , 1);
        }
		else
		{
			$this->error('修改失败！', '/'.FLADMIN.'/Keyword/edit?id='.$_POST["id"] , 3);
		}
    }
    
    public function del()
    {
		if(!empty($_GET["id"])){$id = $_GET["id"];}else{$this->error('删除失败！请重新提交','/'.FLADMIN.'/Keyword' , 3);}if(preg_match('/[0-9]*/',$id)){}else{exit;}
		
		if(M("Keyword")->where("id=$id")->delete())
        {
            S('keywordlist',null); // 删除缓存
            $this->success('删除成功', '/'.FLADMIN.'/Keyword' , 1);
        }
		else
		{
			$this->error('删除失败！请重新提交', '/'.FLADMIN.'/Keyword', 3);
		}
    }
}
