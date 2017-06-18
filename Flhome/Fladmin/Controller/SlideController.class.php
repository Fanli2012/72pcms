<?php
namespace Fladmin\Controller;
use Fladmin\Controller\BaseController;

class SlideController extends BaseController
{
    function _initialize()
	{
		parent::_initialize();
    }
    
    public function index()
    {
        $this->posts = M("Slide")->order('is_show asc,rank desc')->select();
        $this->display();
    }
    
    public function doadd()
    {
		if(M('Slide')->data($_POST)->add())
        {
            $this->success('添加成功！', CMS_ADMIN.'Slide' , 1);
        }
		else
		{
			$this->error('添加失败！请修改后重新添加', CMS_ADMIN.'Slide/add' , 3);
		}
    }
    
    public function add()
    {
        $this->display("edit");
    }
    
    public function edit()
    {
        if(!empty($_GET["id"])){$id = $_GET["id"];}else{$id="";}
        if(preg_match('/[0-9]*/',$id)){}else{exit;}
        
        $this->id = $id;
        $this->row = M('Slide')->where("id=$id")->find();
        $this->display();
    }
    
    public function doedit()
    {
        if(!empty($_POST["id"])){$id = $_POST["id"];unset($_POST["id"]);}else {$id="";exit;}
        
		if(M('Slide')->where("id=$id")->save($_POST))
        {
            $this->success('修改成功！', CMS_ADMIN.'Slide' , 1);
        }
		else
		{
			$this->error('修改失败！', CMS_ADMIN.'Slide/edit?id='.$id , 3);
		}
    }
    
    public function del()
    {
		if(!empty($_GET["id"])){$id = $_GET["id"];}else{$this->error('删除失败！请重新提交',CMS_ADMIN.'Slide' , 3);}if(preg_match('/[0-9]*/',$id)){}else{exit;}
		
		if(M("Slide")->where("id=$id")->delete())
        {
            $this->success('删除成功', CMS_ADMIN.'Slide' , 1);
        }
		else
		{
			$this->error('删除失败！请重新提交', CMS_ADMIN.'Slide', 3);
		}
    }
}