<?php
namespace Fladmin\Controller;
use Fladmin\Controller\BaseController;

class SysconfigController extends BaseController
{
    public function index()
    {
        $this->posts = M("Sysconfig")->order('id desc')->select();
        $this->display();
    }
    
    public function doadd()
    {
        if(!empty($_POST["info"])){$data['info'] = $_POST["info"];}else {$data['info']="";}//参数说明
        if(!empty($_POST["value"])){$data['value'] = $_POST["value"];}else {$data['value']="";}//参数值
        
        //参数名称
        if(!empty($_POST["varname"]))
        {
            preg_match("/^cms_[a-z]+$/i", $_POST["varname"]) ? $data['varname'] = $_POST["varname"] : $data['varname']="";
        }
        else
        {
            $data['varname']="";
        }
        
        $Sysconfig = M('Sysconfig');
        
		if($data['varname']!="" && $Sysconfig->data($data)->add())
        {
            updateconfig(M("Sysconfig")->select());
            $this->success('添加成功！', CMS_ADMIN.'Sysconfig' , 1);
        }
		else
		{
			$this->error('添加失败！请修改后重新添加', CMS_ADMIN.'Sysconfig/add' , 3);
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
        $this->row = M('Sysconfig')->where("id=$id")->find();
        $this->display();
    }
    
    public function doedit()
    {
        if(!empty($_POST["id"])){$id = $_POST["id"];}else {$id="";exit;}
        if(!empty($_POST["info"])){$data['info'] = $_POST["info"];}else {$data['info']="";}//参数说明
        if(!empty($_POST["value"])){$data['value'] = $_POST["value"];}else {$data['value']="";}//参数值
        
        //参数名称
        if(!empty($_POST["varname"]))
        {
            preg_match("/^cms_[a-z]+$/i", $_POST["varname"]) ? $data['varname'] = $_POST["varname"] : $data['varname']="";
        }
        else
        {
            $data['varname']="";
        }
		
        $Sysconfig = M('Sysconfig');
        
		if($data['varname']!="" && $Sysconfig->where("id=$id")->save($data))
        {
            updateconfig(M("Sysconfig")->select());
            $this->success('更新成功！', CMS_ADMIN.'Sysconfig' , 1);
        }
		else
		{
			$this->error('更新失败！请修改后重新提交', CMS_ADMIN.'Sysconfig/edit?id='.$_POST["id"] , 3);
		}
    }
    
    public function del()
    {
		if(!empty($_GET["id"])){$id = $_GET["id"];}else{$this->error('删除失败！请重新提交',CMS_ADMIN.'Sysconfig' , 3);}if(preg_match('/[0-9]*/',$id)){}else{exit;}
		
		if(M("Sysconfig")->where("id=$id")->delete())
        {
            $this->success('删除成功', CMS_ADMIN.'Sysconfig' , 1);
        }
		else
		{
			$this->error('删除失败！请重新提交', CMS_ADMIN.'Sysconfig', 3);
		}
    }
}