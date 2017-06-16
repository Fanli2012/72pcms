<?php
namespace Fladmin\Controller;
use Fladmin\Controller\BaseController;

class UserController extends BaseController
{
    public function index()
    {
        $this->display();
    }
    
    public function edit()
    {
        $this->row = M('User')->where("id=1")->find();
        $this->display();
    }
    
    public function doedit()
    {
        if(!empty($_POST["username"])){$data['username'] = $map['username'] = $_POST["username"];}else{$this->success('用户名不能为空', '/'.FLADMIN.'/User/edit' , 3);exit;}//用户名
        if(!empty($_POST["oldpwd"])){$map['pwd'] = md5($_POST["oldpwd"]);}else{$this->success('旧密码错误', '/'.FLADMIN.'/User/edit' , 3);exit;}
        if($_POST["newpwd"]==$_POST["newpwd2"]){$data['pwd'] = md5($_POST["newpwd"]);}else{$this->success('密码错误', '/'.FLADMIN.'/User/edit' , 3);exit;}
        if($_POST["oldpwd"]==$_POST["newpwd"]){$this->error('新旧密码不能一致！', '/'.FLADMIN.'/User/edit' ,1);exit;}
        
        $User = M("User")->where($map)->find();
        
        if($User)
        {
            if(M('User')->where("id=1")->save($data)){session(null);$this->success('修改成功，请重新登录', '/'.FLADMIN.'/Login' , 3);}
        }
        else
        {
            $this->error('修改失败！旧用户名或密码错误', '/'.FLADMIN.'/User/edit' ,1);
        }
    }
}
