<?php
namespace Fladmin\Controller;
use Fladmin\Controller\BaseController;

class SearchwordController extends BaseController
{
    public function index()
    {
        $this->posts = parent::pageList('searchword');
		$this->display();
    }
    
    public function add()
    {
        $this->display();
    }
    
    public function doadd()
    {
		$_POST['pubdate'] = time();//更新时间
        $_POST['click'] = rand(200,500);//点击
		
		if(isset($_POST['editorValue'])){unset($_POST['editorValue']);}
		
		if($insertId = M('searchword')->add($_POST))
        {
            $this->success('添加成功！', U('Searchword/index'), 1);
        }
		else
		{
			$this->error('添加失败！请修改后重新添加');
		}
    }
    
    public function edit()
    {
        if(!empty($_GET["id"])){$id = $_GET["id"];}else{$id="";}
        if(preg_match('/[0-9]*/',$id)){}else{exit;}
        
        $this->id = $id;
		$this->post = M('searchword')->where('id='.$id)->find();
        $this->display();
    }
    
    public function doedit()
    {
		if(!empty($_POST["id"])){$id = $_POST["id"];unset($_POST["id"]);}else{$id="";exit;}
        if(!empty($_POST["keywords"])){$_POST['keywords']=str_replace("，",",",$_POST["keywords"]);}else{$_POST['keywords']="";}//关键词
        $_POST['pubdate'] = time();//更新时间
        
		if(isset($_POST['editorValue'])){unset($_POST['editorValue']);}
		
		if(M('searchword')->where('id='.$id)->save($_POST))
        {
           $this->success('修改成功！', U('Searchword/index'), 1);
        }
		else
		{
			error_jump('修改失败！');$this->success('修改失败！');
		}
    }
    
	public function del()
    {
		if(!empty($_GET["id"])){$id = $_GET["id"];}else{$this->error('删除失败！请重新提交');}if(preg_match('/[0-9]*/',$id)){}else{exit;}
		
		if(M("searchword")->where("id in ($id)")->delete())
        {
            $this->success('删除成功');
        }
		else
		{
			$this->error('删除失败！请重新提交');
		}
    }
}
