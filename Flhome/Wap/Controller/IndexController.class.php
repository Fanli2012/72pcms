<?php
namespace Wap\Controller;
use Wap\Controller\BaseController;

class IndexController extends BaseController
{
    public function index()
	{
        //echo "<pre>";
        //print_r(get_pre_next(array('aid'=>3,'typeid'=>2,'type'=>"pre")));
        $this->display();
    }
    
	public function category()
	{
        if(!empty($_GET["cat"]) && preg_match('/[0-9]+/',$_GET["cat"])){$cat=$_GET["cat"];}else{$this->error('非法操作！', '/' , 3);exit;}
        $this->cat = $cat;
		
		if(S("catid$cat")){$post=S("catid$cat");}else{$post = M('Arctype')->cache("catid$cat",2592000)->where("id=$cat")->find();}
        $this->post = $post;
		
		$subcat="";$sql="";
		if(S("catreid$cat")){$post=S("catreid$cat");}else{$post=M('Arctype')->cache("catreid$cat",2592000)->field('id')->where("reid=$cat")->find();}
        foreach($post as $row){$subcat=$subcat."typeid=".$row["id"]." or ";}
		$subcat=$subcat."typeid=".$cat;
		$sql=$subcat." or typeid2 in (".$cat.")";//echo $subcat2;exit;
		
		$counts=M("Article")->where($sql)->count();
		if($counts>cms_maxarc){$counts=cms_maxarc;}
		
		$pagesize=cms_pagesize; //默认每页15条，可以把cms_pagesize改成想要的数量
		if($counts % $pagesize){ //取总数据量除以每页数的余数
		$pages = intval($counts/$pagesize) + 1; //如果有余数，则页数等于总数据量除以每页数的结果取整再加一,如果没有余数，则页数等于总数据量除以每页数的结果
		}else{ $pages = $counts/$pagesize; }
		$page=0;
		if(!empty($_GET["page"])){ if($_GET["page"]==1 || $_GET["page"]>$pages){header("HTTP/1.0 404 Not Found");$this->error('操作失败');exit;} $page = $_GET["page"]-1;$nextpage=$_GET["page"]+1;$previouspage=$_GET["page"]-1; }else{ $page = 0;$nextpage=2;$previouspage=0; }
		$start=$page*$pagesize;
		
		$this->posts=arclist(array("sql"=>$sql,"limit"=>"$start,$pagesize"));
		$this->pagenav=get_prenext(array("counts"=>$counts,"pagesize"=>$pagesize,"pagenow"=>$page+1,"catid"=>$cat)); //获取分页列表
		
		$this->display();
    }
	
	public function detail()
	{
		if(!empty($_GET["id"]) && preg_match('/[0-9]+/',$_GET["id"])){$id = $_GET["id"];}else{$this->error('非法操作！', '/' , 3);exit;}
		
		if(S("detailid$id")){$post=S("detailid$id");}else{$post = M('Article')->cache("detailid$id",2592000)->where("id=$id")->find();}
		if($post)
        {
            $this->post=$post;
            $this->pre=get_article_prenext(array('aid'=>$post["id"],'typeid'=>$post["typeid"],'type'=>"pre"));
        }
        else
        {
            $this->error('非法操作！', '/' , 3);exit;
        }
        
        $this->display();
    }
    
    public function page()
	{
		if(!empty($_GET["id"]) && preg_match('/[a-z0-9]+/',$_GET["id"])){$id = $map['filename'] = $_GET["id"];}else{$this->error('非法操作！', '/' , 3);exit;}
		
        if(S("pageid$id")){$post=S("pageid$id");}else{$post = M('Page')->cache("pageid$id",2592000)->where($map)->find();}
		if($post)
        {
            $this->post = $post;
        }
        else
        {
            $this->error('非法操作！', '/' , 3);exit;
        }
        
        $this->display();
    }
    
    //标签详情页
	public function tag()
	{
		if(!empty($_GET["tag"]) && preg_match('/[0-9]+/',$_GET["tag"])){$tag=$_GET["tag"];}else{$this->error('非法操作！', '/' , 3);exit;}
        
		if(S("tagid$tag")){$post=S("tagid$tag");}else{$post = M('Tagindex')->cache("tagid$tag",2592000)->where("id=$tag")->find();}
        $this->post = $post;
		
		$counts=M("Taglist")->where("tid=$tag")->count('aid');
		if($counts>cms_maxarc){$counts=cms_maxarc;}
		$pagesize=cms_pagesize;$page=0;
		if($counts % $pagesize){//取总数据量除以每页数的余数
		$pages = intval($counts/$pagesize) + 1; //如果有余数，则页数等于总数据量除以每页数的结果取整再加一,如果没有余数，则页数等于总数据量除以每页数的结果
		}else{$pages = $counts/$pagesize;}
		if(!empty($_GET["page"])){if($_GET["page"]==1 || $_GET["page"]>$pages){header("HTTP/1.0 404 Not Found");$this->error('操作失败');exit;}$page = $_GET["page"]-1;$nextpage=$_GET["page"]+1;$previouspage=$_GET["page"]-1;}else{$page = 0;$nextpage=2;$previouspage=0;}
		$this->page = $page;
		$this->pages = $pages;
		$this->counts = $counts;
		$start=$page*$pagesize;
		
		$posts=M("Taglist")->where("tid=$tag")->order('aid desc')->limit("$start,$pagesize")->select();
		foreach($posts as $row)
		{
			$aid[] = $row["aid"];
		}
		$aid = implode(',',$aid);
		
		$this->posts=arclist(array("sql"=>"id in ($aid)","orderby"=>"id desc","limit"=>"$pagesize")); //获取列表
		$this->pagenav=get_prenext(array("counts"=>$counts,"pagesize"=>$pagesize,"pagenow"=>$page+1,"catid"=>$tag,"urltype"=>"tag")); //获取分页列表
		
		$this->display($post['template']);
    }
    
	//标签页
    public function tags()
	{
		$this->display();
    }
    
    //搜索页
	public function search()
	{
		if(!empty($_GET["keyword"]))
		{
			$keyword = $_GET["keyword"]; //搜索的关键词
			if(strstr($keyword,"&")) exit;
			
			$map['title'] = array('LIKE',"%$keyword%");
			
			$this->posts=M("Article")->field('body',true)->where($map)->order('id desc')->limit(30)->select();
			$this->keyword = $keyword;
		}
		else
		{
			$this->error('请输入正确的关键词', '/' , 3);exit;
		}
		
		$this->display();
        // /search?keyword=百度
    }
}
