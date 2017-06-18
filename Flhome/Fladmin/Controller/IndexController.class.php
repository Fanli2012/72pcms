<?php
namespace Fladmin\Controller;
use Fladmin\Controller\BaseController;

class IndexController extends BaseController
{
	public function index()
	{
		$this->menus = D('Menu')->getPermissionsMenu(session('admin_user_info')['role_id']);
		
        $this->display();
    }
	
    public function welcome()
	{
        $this->display();
    }
	
    public function upconfig()
	{
        updateconfig();
        $this->success('缓存更新成功！');
    }
    
    public function upcache()
	{
        dir_delete(APP_PATH.'Runtime/');
        $this->success('缓存更新成功！');
    }
	
	public function test()
	{
		for ($x=1; $x<=103; $x++)
		{
			$User = M("access"); // 实例化User对象
			$data['role_id'] = 1;
			$data['menu_id'] = $x;
			$User->add($data);
		}
    }
}