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
		
		if(!session('?admin_user_info'))
		{
			$this->error('您访问的页面不存在或已被删除！', '/',3);
		}
        else
        {
            $this->user_info = session('admin_user_info');
        }
    }
	
    //设置空操作
    public function _empty()
    {
        $this->error('您访问的页面不存在或已被删除！');
    }
}