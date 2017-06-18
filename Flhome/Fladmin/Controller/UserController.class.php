<?php
namespace Fladmin\Controller;
use Fladmin\Controller\BaseController;

class UserController extends BaseController
{
    public function index()
    {
        $posts = parent::pageList('user');
		
        $this->posts = $posts;
		$this->display();
    }
	
	public function add()
    {
		$this->rolelist = M('user_role')->order('listorder desc')->select();
		
        $this->display();
    }
    
    public function doadd()
    {
		$_POST['pwd'] = md5($_POST['pwd']);
		if(M('user')->data($_POST)->add())
        {
			$this->success('添加成功！', CMS_ADMIN.'User' , 1);
        }
		else
		{
			$this->error('添加失败！请修改后重新添加', CMS_ADMIN.'User/add' , 3);
		}
    }
    
    public function edit()
    {
		if(!empty($_GET["id"])){$id = $_GET["id"];}else{$id="";}
        if(preg_match('/[0-9]*/',$id)){}else{exit;}
        
        $this->id = $id;
		$this->post = M('user')->where('id='.$id)->find();
        $this->rolelist = M('user_role')->order('listorder desc')->select();
		
        $this->display();
    }
	
	public function doedit()
    {
        if(!empty($_POST["id"])){$id = $_POST["id"];unset($_POST["id"]);}else {$id="";exit;}
        
		unset($_POST["_token"]);
		$_POST['pwd'] = md5($_POST['pwd']);
		if(M('user')->where('id='.$id)->save($_POST))
        {
            $this->success('修改成功！', CMS_ADMIN.'User' , 1);
        }
		else
		{
			$this->error('修改失败！', CMS_ADMIN.'User' , 3);
		}
    }
	
	public function del()
    {
		if(!empty($_GET["id"])){$id = $_GET["id"];}else{$this->error('删除失败！请重新提交');}
		
		if(M("user")->where("id in ($id)")->delete())
        {
            $this->success('删除成功');
        }
		else
		{
			$this->error('删除失败！请重新提交');
		}
    }
    
    /* public function edit()
    {
        $this->row = M('User')->where("id=1")->find();
        $this->display();
    }
    
    public function doedit()
    {
        if(!empty($_POST["username"])){$data['username'] = $map['username'] = $_POST["username"];}else{$this->success('用户名不能为空', CMS_ADMIN.'User/edit' , 3);exit;}//用户名
        if(!empty($_POST["oldpwd"])){$map['pwd'] = md5($_POST["oldpwd"]);}else{$this->success('旧密码错误', CMS_ADMIN.'User/edit' , 3);exit;}
        if($_POST["newpwd"]==$_POST["newpwd2"]){$data['pwd'] = md5($_POST["newpwd"]);}else{$this->success('密码错误', CMS_ADMIN.'User/edit' , 3);exit;}
        if($_POST["oldpwd"]==$_POST["newpwd"]){$this->error('新旧密码不能一致！', CMS_ADMIN.'User/edit' ,1);exit;}
        
        $User = M("User")->where($map)->find();
        
        if($User)
        {
            if(M('User')->where("id=1")->save($data)){session(null);$this->success('修改成功，请重新登录', CMS_ADMIN.'Login' , 3);}
        }
        else
        {
            $this->error('修改失败！旧用户名或密码错误', CMS_ADMIN.'User/edit' ,1);
        }
    } */
}
