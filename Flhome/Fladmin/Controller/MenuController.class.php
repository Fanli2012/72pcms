<?php
namespace Fladmin\Controller;
use Fladmin\Controller\BaseController;

class MenuController extends BaseController
{
    public function index()
    {
        $posts = parent::pageList('menu');
		
        $this->posts = $posts;
		$this->display();
    }
	
	public function add()
    {
		if(!empty($_GET["pid"])){$pid = $_GET["pid"];}else{$pid=0;}
        
        $this->pid = $pid;
        $this->menu = D('Menu')->category_tree(D('Menu')->get_category('menu',0));
		
        $this->display();
    }
    
    public function doadd()
    {
		$menuid = M('menu')->data($_POST)->add();
		if($menuid)
        {
			M('access')->data(array('role_id' => 1, 'menu_id' => $menuid))->add();
			
			$this->success('添加成功！', CMS_ADMIN.'Menu' , 1);
        }
		else
		{
			$this->error('添加失败！请修改后重新添加', CMS_ADMIN.'Menu/add' , 3);
		}
    }
    
    public function edit()
    {
		if(!empty($_GET["id"])){$id = $_GET["id"];}else{$id="";}
        if(preg_match('/[0-9]*/',$id)){}else{exit;}
        
        $this->id = $id;
		$this->post = M('menu')->where('id='.$id)->find();
        $this->menu = D('Menu')->category_tree(D('Menu')->get_category('menu',0));
		
        $this->display();
    }
	
	public function doedit()
    {
        if(!empty($_POST["id"])){$id = $_POST["id"];unset($_POST["id"]);}else {$id="";exit;}
        
		if(M('menu')->where('id='.$id)->save($_POST))
        {
            $this->success('修改成功！', CMS_ADMIN.'Menu' , 1);
        }
		else
		{
			$this->error('修改失败！', CMS_ADMIN.'Menu' , 3);
		}
    }
	
	public function del()
    {
		if(!empty($_GET["id"])){$id = $_GET["id"];}else{$this->error('删除失败！请重新提交');}
		
		if(M("menu")->where("id in ($id)")->delete())
        {
			M('access')->where('role_id=1')->where("menu_id in ($id)")->delete();
			
            $this->success('删除成功');
        }
		else
		{
			$this->error('删除失败！请重新提交');
		}
    }
}