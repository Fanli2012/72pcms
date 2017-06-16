<?php
namespace Fladmin\Controller;
use Fladmin\Controller\BaseController;

class GuestbookController extends BaseController
{
	public function _initialize()
	{
		parent::_initialize();
    }
	
    public function index()
    {
		$where = array();
        if(!empty($_REQUEST["keyword"]))
        {
            $where['title'] = array('like','%'.$_REQUEST['keyword'].'%');
        }
        
        $posts = parent::pageList('guestbook',$where);
		
        $this->posts = $posts;
		$this->display();
    }
    
    public function del()
    {
		if(!empty($_GET["id"])){$id = $_GET["id"];}else{$this->error('删除失败！请重新提交','/'.FLADMIN.'/Guestbook' , 3);}
		
		if(M("guestbook")->where("id in ($id)")->delete())
        {
            $this->success("$id ,删除成功", '/'.FLADMIN.'/Guestbook' , 1);
        }
		else
		{
			$this->error("$id ,删除失败！请重新提交", '/'.FLADMIN.'/Guestbook', 3);
		}
    }
}
