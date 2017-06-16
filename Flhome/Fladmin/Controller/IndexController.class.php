<?php
namespace Fladmin\Controller;
use Fladmin\Controller\BaseController;

class IndexController extends BaseController
{
	public function index()
	{
        $this->display();
    }
	
    public function upconfig()
	{
        updateconfig();
        $this->success('缓存更新成功！', '/'.FLADMIN , 1);
    }
    
    public function upcache()
	{
        dir_delete(APP_PATH.'Runtime/');
        $this->success('缓存更新成功！', '/'.FLADMIN , 1);
    }
}