<?php
namespace Fladmin\Controller;
use Fladmin\Controller\BaseController;

class TagController extends BaseController
{
    public function index()
    {
        $counts = M("Tagindex")->field('id')->count();
		
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
		
        $this->posts = M("Tagindex")->order('id desc')->limit($start,$pagesize)->select();
        $this->display();
    }
    
    public function doadd()
    {
        if(!empty($_POST["tag"])){$data['tag'] = $_POST["tag"];}else {$data['tag']="";}//tag名称
        if(!empty($_POST["title"])){$data['title'] = $_POST["title"];}else {$data['title']="";}//seotitle
        if(!empty($_POST["description"])){$data['description'] = $_POST["description"];}else {$data['description']="";}//tag描述
        if(!empty($_POST["filename"])){$data['filename'] = $_POST["filename"];}else {$data['filename']="";}//seotitle
        if(!empty($_POST["template"])){$data['template'] = $_POST["template"];}else {$data['template']="";}//tag描述
        if(!empty($_POST["content"])){$data['content'] = $_POST["content"];}else {$data['content']="";}//tag内容
        if(!empty($_POST["litpic"])){$data['litpic'] = $_POST["litpic"];}else {$data['litpic']="";}//缩略图
        if(!empty($_POST["keywords"])){$data['keywords']=str_replace("，",",",$_POST["keywords"]);}else {$data['keywords']="";}//关键词
        $tagarc="";if(!empty($_POST["tagarc"])){$tagarc = str_replace("，",",",$_POST["tagarc"]);unset($_POST["tagarc"]);if(!preg_match("/^\d*$/",str_replace(",","",$tagarc))){$tagarc="";}} //Tag文章列表
        
        $data['pubdate'] = time();//更新时间
        $data['click'] = rand(200,500);//点击
        
		if($insertId = M('Tagindex')->data($data)->add())
        {
            if($tagarc!="")
            {
                $arr=explode(",",$tagarc);
                
                foreach($arr as $row)
                {
                    $data['tid'] = $insertId;
                    $data['aid'] = $row;
                    M("Taglist")->add($data);
                }
            }
            $this->success('添加成功！', U('Tag/index'), 1);
        }
		else
		{
			$this->error('添加失败！请修改后重新添加', U('Tag/add'), 3);
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
        $this->row = M('Tagindex')->where("id=$id")->find();
        
        //获取该标签下的文章id
        $posts = M('Taglist')->field('aid')->where("tid=$id")->select();
        $aidlist = "";
        if(!empty($posts))
        {
            foreach($posts as $row)
            {
                $aidlist=$aidlist.','.$row['aid'];
            }
        }
        $this->aidlist = ltrim($aidlist, ",");
        
        $this->display();
    }
    
    public function doedit()
    {
        if(!empty($_POST["id"])){$id = $_POST["id"];}else {$id="";exit;}
        if(!empty($_POST["tag"])){$data['tag'] = $_POST["tag"];}else {$data['tag']="";}//tag名称
        if(!empty($_POST["title"])){$data['title'] = $_POST["title"];}else {$data['title']="";}//seotitle
        if(!empty($_POST["description"])){$data['description'] = $_POST["description"];}else {$data['description']="";}//tag描述
        if(!empty($_POST["filename"])){$data['filename'] = $_POST["filename"];}else {$data['filename']="";}//seotitle
        if(!empty($_POST["template"])){$data['template'] = $_POST["template"];}else {$data['template']="";}//tag描述
        if(!empty($_POST["content"])){$data['content'] = $_POST["content"];}else {$data['content']="";}//tag内容
        if(!empty($_POST["litpic"])){$data['litpic'] = $_POST["litpic"];}else {$data['litpic']="";}//缩略图
        if(!empty($_POST["keywords"])){$data['keywords']=str_replace("，",",",$_POST["keywords"]);}else {$data['keywords']="";}//关键词
        $data['pubdate'] = time();//更新时间
        $tagarc="";
		if(!empty($_POST["tagarc"])){$tagarc = str_replace("，",",",$_POST["tagarc"]);unset($_POST["tagarc"]);if(!preg_match("/^\d*$/",str_replace(",","",$tagarc))){$tagarc="";}} //Tag文章列表
        
        $Tagindex=M('Tagindex');
        
		if($Tagindex->where("id=$id")->save($data))
        {
            $Taglist=M("Taglist");
            //获取该标签下的文章id
            $posts = $Taglist->field('aid')->where("tid=$id")->select();
            $aidlist = "";
            if(!empty($posts))
            {
                foreach($posts as $row)
                {
                    $aidlist=$aidlist.','.$row['aid'];
                }
            }
            $aidlist = ltrim($aidlist, ",");
            
            if($tagarc!="" && $tagarc!=$aidlist)
            {
                $Taglist->where("tid=$id")->delete();
                
                if(!empty($tagarc))
                {
                    $arr=explode(",",$tagarc);
                    
                    foreach($arr as $row)
                    {
                        $data['tid'] = $id;
                        $data['aid'] = $row;
                        $Taglist->add($data);
                    }
                }
            }
            
            $this->success('修改成功！', U('Tag/index'), 1);
        }
		else
		{
			$this->error('修改失败！', U('Tag/edit',array('id'=>$_POST["id"])), 3);
		}
    }
    
	public function del()
    {
		if(!empty($_GET["id"])){$id = $_GET["id"];}else{$this->error('删除失败！请重新提交', U('Tag/index'), 3);}if(preg_match('/[0-9]*/',$id)){}else{exit;}
		
		if(M("Tagindex")->where("id=$id")->delete())
        {
            $this->success('删除成功', U('Tag/index'), 1);
        }
		else
		{
			$this->error('删除失败！请重新提交', U('Tag/index'), 3);
		}
    }
}
