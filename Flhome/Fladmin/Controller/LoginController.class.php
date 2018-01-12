<?php
namespace Fladmin\Controller;
use Think\Controller;

class LoginController extends Controller
{
    /**
     * 登录页面
     */
	public function index()
	{
		if(session('?admin_user_info'))
		{
			header("Location: ".U('Index/index'));
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
        
        if($User)
        {
            $User['rolename'] = M("user_role")->where("id=".$User['role_id'])->getField('name');
            session("username", $User['username']);
            session("uid", $User["id"]);
            session("admin_user_info", $User);
			$this->success('登录成功！', U('Index/index'), 1);
        }
        else
        {
            $this->error('用户名或密码错误！！', U('Login/index'),3);
        }
    }

    //退出登录
    public function loginout()
    {
        session(null); // 清空当前的session
		$this->success('退出成功！', '/');
    }
    
    //密码恢复
    public function recoverpwd()
    {
        $data["username"] = "admin888";
        $data["pwd"] = "21232f297a57a5a743894a0e4a801fc3";
        
        if(M('user')->where("id=1")->save($data))
        {
            $this->success('密码恢复成功！', U('Login/index'), 1);
        }
		else
		{
			$this->error('密码恢复失败！', U('Login/index'), 3);
		}
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
}