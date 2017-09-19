<?php
namespace Fladmin\Controller;
use Fladmin\Controller\BaseController;

class ProductController extends BaseController
{
	function _initialize()
	{
		parent::_initialize();
    }
	
    public function index()
    {
        $where = array();
        if(!empty($_REQUEST["keyword"]))
        {
            $where['title'] = array('like','%'.$_REQUEST['keyword'].'%');
        }
        if($_REQUEST["typeid"]!=0 && !empty($_REQUEST["typeid"]))
        {
            $where['typeid'] = $_REQUEST["typeid"];
        }
        if(!empty($_REQUEST["id"]))
        {
            $where['typeid'] = $_REQUEST["id"];
        }
        
        $posts = parent::pageList('Product',$where);
		foreach($posts as $key=>$value)
        {
            $info = M('ProductType')->field('content',true)->where("id=".$value['typeid'])->find();
            $posts[$key]['typename'] = $info['typename'];
        }
        
        $this->posts = $posts;
		$this->display();
		
		
		
		
		
        //if(!empty($_GET["id"])){$id = $_GET["id"];}else {$id="";}if(preg_match('/[0-9]*/',$id)){}else{exit;}
        
        /* if(!empty($id)){$map['typeid']=$id;}
        $Product = M("Product")->field('id')->where($map);
        $counts = $Product->count();
        
        $pagesize =cms_pagesize;$page =0;
        if($counts % $pagesize){ //取总数据量除以每页数的余数
        $pages = intval($counts/$pagesize) + 1; //如果有余数，则页数等于总数据量除以每页数的结果取整再加一,如果没有余数，则页数等于总数据量除以每页数的结果
        }else{$pages = $counts/$pagesize;}
        if(!empty($_GET["page"])){$page = $_GET["page"]-1;$nextpage=$_GET["page"]+1;$previouspage=$_GET["page"]-1;}else{$page = 0;$nextpage=2;$previouspage=0;}
        if($counts>0){if($page>$pages-1){exit;}}
        $start = $page*$pagesize;
        $Product = M("Product")->field('id,typeid,title,pubdate,click,litpic,tuijian')->where($map)->order('id desc')->limit($start,$pagesize)->select();
        
        $this->counts = $counts;
		$this->pages = $pages;
        $this->page = $page;
        $this->nextpage = $nextpage;
        $this->previouspage = $previouspage;
        $this->id = $id;
        $this->posts = $Product;
        
        //echo '<pre>';
        //print_r($Product);
        $this->display(); */
    }
    
	public function aa()
    {
        $this->display();
    }
	
    public function add()
    {
		if(!empty($_GET["catid"])){$this->catid = $_GET["catid"];}else{$this->catid = 0;}
		
        $this->display('edit');
    }
    
    public function doadd()
    {
        $litpic="";if(!empty($_POST["litpic"])){$litpic = $_POST["litpic"];}else{$_POST['litpic']="";} //缩略图
        if(empty($_POST["description"])){if(!empty($_POST["body"])){$_POST['description']=cut_str($_POST["body"]);}} //description
        $_POST['addtime'] = $_POST['pubdate'] = time(); //添加&更新时间
		$_POST['user_id'] = $_SESSION['admin_user_info']['id']; // 发布者id
		
		//关键词
        if(!empty($_POST["keywords"]))
		{
			$_POST['keywords']=str_replace("，",",",$_POST["keywords"]);
		}
		else
		{
			if(!empty($_POST["title"]))
			{
				$title=$_POST["title"];
				$title=str_replace("，","",$title);
				$title=str_replace(",","",$title);
				$_POST['keywords']=get_keywords($title);//标题分词
			}
		}
		
        $Product = M('Product');
        
		if($Product->data($_POST)->add())
        {
            $this->success('添加成功！', CMS_ADMIN.'Product' , 1);
        }
		else
		{
			$this->error('添加失败！请修改后重新添加', CMS_ADMIN.'Product/add' , 3);
		}
    }
    
    public function edit()
    {
        if(!empty($_GET["id"])){$id = $_GET["id"];}else {$id="";}if(preg_match('/[0-9]*/',$id)){}else{exit;}
        
        $this->id = $id;
        $this->post = M('Product')->where("id=$id")->find();
        $this->display();
    }
    
    public function doedit()
    {
        if(!empty($_POST["id"])){$id = $_POST["id"];}else {$id="";exit;}
        
        $litpic="";if(!empty($_POST["litpic"])){$litpic = $_POST["litpic"];}else{$_POST['litpic']="";} //缩略图
        if(empty($_POST["description"])){if(!empty($_POST["body"])){$_POST['description']=cut_str($_POST["body"]);}}//description
        $_POST['pubdate'] = time();//更新时间
        $_POST['user_id'] = $_SESSION['admin_user_info']['id']; // 修改者id
		
		//关键词
        if(!empty($_POST["keywords"]))
		{
			$_POST['keywords']=str_replace("，",",",$_POST["keywords"]);
		}
		else
		{
			if(!empty($_POST["title"]))
			{
				$title=$_POST["title"];
				$title=str_replace("，","",$title);
				$title=str_replace(",","",$title);
				$_POST['keywords']=get_keywords($title);//标题分词
			}
		}
        
        $Product = M('Product');
        
        if($Product->where("id=$id")->save($_POST))
        {
            $this->success('修改成功！', CMS_ADMIN.'Product' , 1);
        }
		else
		{
			$this->error('修改失败！', CMS_ADMIN.'Product/edit?id='.$_POST["id"] , 3);
		}
    }
    
    public function del()
    {
		if(!empty($_GET["id"])){$id = $_GET["id"];}else{$this->error('删除失败！请重新提交',CMS_ADMIN.'Product' , 3);}if(preg_match('/[0-9]*/',$id)){}else{exit;}
		
		if(M("Product")->where("id in ($id)")->delete())
        {
            $this->success("$id ,删除成功", CMS_ADMIN.'Product' , 1);
        }
		else
		{
			$this->error("$id ,删除失败！请重新提交", CMS_ADMIN.'Product', 3);
		}
    }
    
	//商品推荐
	public function recommendarc()
    {
		if(!empty($_GET["id"])){$id = $_GET["id"];}else{$this->error('删除失败！请重新提交',CMS_ADMIN.'Product' , 3);}if(preg_match('/[0-9]*/',$id)){}else{exit;}
		
		$Product = M("Product");
		$data['tuijian'] = 1;

        if($Product->where("id in ($id)")->save($data))
        {
            $this->success("$id ,推荐成功", CMS_ADMIN.'Product', 1);
        }
		else
		{
			$this->error("$id ,推荐失败！请重新提交", CMS_ADMIN.'Product', 3);
		}
    }
    
	//商品是否存在
    public function productexists()
    {
        if(!empty($_GET["title"]))
        {
            $map['title'] = $_GET["title"];
        }
        else
        {
            $map['title']="";
        }
        
        if(!empty($_GET["id"]))
        {
            $map['id'] = array('NEQ',$_GET["id"]);
        }
        
        $Product = M("Product")->where($map);
        
        echo $Product->count();
    }
}
