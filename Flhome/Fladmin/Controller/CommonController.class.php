<?php
namespace Fladmin\Controller;
use Think\Controller;

class CommonController extends Controller
{
	function _initialize()
	{
		$this->action_name = ACTION_NAME;
		$this->controller_name = CONTROLLER_NAME;
		$this->module_name = MODULE_NAME;
		
		$route = MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME;
		
		if(!session('?admin_user_info'))
		{
			$this->error('您访问的页面不存在或已被删除！', '/',3);
		}
        else
        {
            $this->user_info = session('admin_user_info');
        }
		
		//判断是否拥有权限
		if(session('admin_user_info')['role_id'] <> 1)
		{
			$uncheck = array('Fladmin/Index/index','Fladmin/Index/upconfig','Fladmin/Index/upcache','Fladmin/Index/welcome');

			if(in_array($route, $uncheck))
			{
				
			}
			else
			{
				$menu_id = M('menu')->where(['module'=>MODULE_NAME, 'controller'=>CONTROLLER_NAME, 'action'=>ACTION_NAME])->getField('id');
				if(!$menu_id){$this->error('你没有权限访问，请联系管理员！', CMS_ADMIN, 3);}
				
				$check = M('access')->where(['role_id' => session('admin_user_info')['role_id'], 'menu_id' => $menu_id])->find();
				
				if(!$check)
				{
					$this->error('你没有权限访问，请联系管理员！', CMS_ADMIN, 3);
				}
			}
        }
    }
	
    //设置空操作
    public function _empty()
    {
        $this->error('您访问的页面不存在或已被删除！');
    }
}