<?php
namespace Home\Controller;
use Home\Controller\BaseController;

class IndexController extends BaseController
{
    //首页
    public function index()
	{
        $this->display();
    }
	
    //列表页
    public function category()
	{
		if(!empty($_GET["cat"]) && preg_match('/[0-9]+/',$_GET["cat"])){$cat=$_GET["cat"];}else{$this->error('非法操作！', '/' , 3);exit;}
        
		if(S("catid$cat")){$post=S("catid$cat");}else{$post = M('Arctype')->cache("catid$cat",2592000)->where("id=$cat")->find();}
        $this->post = $post;
		
		$subcat="";$sql="";
		if(S("catreid$cat")){$post=S("catreid$cat");}else{$post=M('Arctype')->cache("catreid$cat",2592000)->field('id')->where("reid=$cat")->select();}
		foreach($post as $row){$subcat=$subcat."typeid=".$row["id"]." or ";}
		$subcat=$subcat."typeid=".$cat;
		$sql=$subcat." or typeid2 in (".$cat.")";//echo $subcat2;exit;
		$this->sql = $sql;
		
		$counts=M("Article")->where($sql)->count('id');
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
		
		$this->posts = arclist(array("sql"=>$sql,"limit"=>"$start,$pagesize")); //获取列表
		$this->pagenav = get_listnav(array("counts"=>$counts,"pagesize"=>$pagesize,"pagenow"=>$page+1,"catid"=>$cat)); //获取分页列表
    
		$this->display($post['templist']);
	}
    
    //文章详情页
    public function detail()
	{
        if(!empty($_GET["id"]) && preg_match('/[0-9]+/',$_GET["id"])){$id = $_GET["id"];}else{$this->error('非法操作！', '/' , 3);exit;}
		
		if(S("detailid$id")){$post=S("detailid$id");}else{$post = M('Article')->cache("detailid$id",2592000)->where("id=$id")->find();}
		if($post)
        {
			$cat=$post['typeid'];
            $post['body']=ReplaceKeyword($post['body']);
			$this->post = $post;
            $this->pre=get_article_prenext(array('aid'=>$post["id"],'typeid'=>$post["typeid"],'type'=>"pre"));
        }
        else
        {
            $this->error('非法操作！', '/' , 3);exit;
        }
        
		if(S("catid$cat")){$post=S("catid$cat");}else{$post = M('Arctype')->cache("catid$cat",2592000)->where("id=$cat")->find();}
        $this->display($post['temparticle']);
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
		$this->pagenav=get_listnav(array("counts"=>$counts,"pagesize"=>$pagesize,"pagenow"=>$page+1,"catid"=>$tag,"urltype"=>"tag")); //获取分页列表
		
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
    }
    
    //单页面
    public function page()
	{
        if(!empty($_GET["id"]) && preg_match('/[a-z0-9]+/',$_GET["id"])){$id = $map['filename'] = $_GET["id"];}else{$this->error('非法操作！', '/' , 3);exit;}
		
        if(S("pageid$id")){$post=S("pageid$id");}else{$post = M('Page')->cache("pageid$id",2592000)->where($map)->find();}
		if($post)
        {
            $this->post=$post;
        }
        else
        {
            $this->error('非法操作！', '/' , 3);exit;
        }
        
		$this->display($post['template']);
    }
    
	/**
     * 登录页面
     */
    public function login()
    {
		if(session('?username'))
		{
			header("Location: /".FLADMIN);
			exit;
		}
        $this->display();
    }
    
    /**
     * 登录处理页面
     */
    public function dologin()
    {
        if(!empty($_POST["username"])){$username = $_POST["username"];}else{$username='';}//用户名
        if(!empty($_POST["pwd"])){$pwd = md5($_POST["pwd"]);}else{$pwd='';}//密码
		
		$sql = "(username = '".$username."' and pwd = '".$pwd."') or (email = '".$username."' and pwd = '".$pwd."')";
        $User = M("User")->where($sql)->find();
        $User['rolename'] = M("UserRole")->where("id=".$User['role_id'])->getField('rolename');
		
        if($User)
        {
            session("username", $User['username']);
            session("uid", $User["id"]);
            session("admin_user_info", $User);
			$this->success('登录成功！', '/'.FLADMIN , 1);
        }
        else
        {
            $this->error('登录失败！请重新登录！！', '/'.FLLOGIN ,1);
        }
    }

    //退出登录
    public function loginout()
    {
        session(null); // 清空当前的session
		$this->success('退出成功！', '/');
    }
	
	/**
     * 判断用户名是否存在
     */
    public function userexists()
    {
        if(!empty($_POST["username"]))
        {
            $map['username'] = $_POST["username"];
        }
        else
        {
            $map['username']="";
        }
        
        $User = M("User")->where($map);
        
        echo $User->count();
    }
	
	/**
     * 404页面
     */
    public function nofound()
    {
        //echo '您访问的页面不存在或已被删除';
        $this->display();
    }
	
	public function test()
    {
		//echo (dirname('/images/uiui/1.jpg'));
		//echo '<pre>';
		$str='<p><img border="0" src="./images/1.jpg" alt=""/></p>';
		
		//echo getfirstpic($str);
		$imagepath='.'.getfirstpic($str);
		$image = new \Think\Image(); 
		$image->open($imagepath);
		// 按照原图的比例生成一个最大为240*180的缩略图并保存为thumb.jpg
		$image->thumb(cms_imgwidth, cms_imgheight)->save('./images/1thumb.jpg');
    }
}