<?php
namespace Fladmin\Controller;
use Fladmin\Controller\BaseController;

class UserRoleController extends BaseController
{
    public function index()
    {
		$posts = parent::pageList('user_role', '', 'listorder desc');
		
        $this->posts = $posts;
		$this->display();
    }
	
	public function add()
    {
		$this->display();
    }
    
    public function doadd()
    {
		if(M('user_role')->data($_POST)->add())
        {
			$this->success('添加成功！', CMS_ADMIN.'UserRole' , 1);
        }
		else
		{
			$this->error('添加失败！请修改后重新添加', CMS_ADMIN.'UserRole' , 3);
		}
    }
    
    public function edit()
    {
		if(!empty($_GET["id"])){$id = $_GET["id"];}else{$id="";}
        if(preg_match('/[0-9]*/',$id)){}else{exit;}
        
        $this->id = $id;
		$this->post = M('user_role')->where('id='.$id)->find();
		
        $this->display();
    }
	
	public function doedit()
    {
        if(!empty($_POST["id"])){$id = $_POST["id"];unset($_POST["id"]);}else {$id="";exit;}
        
		if(M('user_role')->where('id='.$id)->save($_POST))
        {
            $this->success('修改成功！', CMS_ADMIN.'UserRole' , 1);
        }
		else
		{
			$this->error('修改失败！', CMS_ADMIN.'UserRole' , 3);
		}
    }
	
	public function del()
    {
		if(!empty($_GET["id"])){$id = $_GET["id"];}else{$this->error('删除失败！请重新提交');}
		
		if(M("user_role")->where("id in ($id)")->delete())
        {
            $this->success('删除成功');
        }
		else
		{
			$this->error('删除失败！请重新提交');
		}
    }
	
	//角色权限设置视图
	public function permissions()
    {
		if(!empty($_GET["id"])){$role_id = $_GET["id"];}else{$this->error('您访问的页面不存在或已被删除！');}
		
		$menu = [];
		$access = M('access')->where('role_id='.$role_id)->select();
		if($access)
		{
			foreach($access as $k=>$v)
			{
				$menu[] = $v['menu_id'];
			}
		}
		
		$menus = $this->category_tree($this->get_category('menu',0));
		foreach($menus as $k=>$v)
		{
			$menus[$k]['is_access'] = 0;
			
			if(!empty($menu) && in_array($v['id'], $menu))
			{
				$menus[$k]['is_access'] = 1;
			}
		}
		
		$this->menus = $menus;
		$this->role_id = $role_id;
		$this->display();
    }
	
	//角色权限设置
	public function dopermissions()
    {
		$menus = [];
		if($_POST['menuid'] && $_POST['role_id'])
		{
			foreach($_POST['menuid'] as $row)
			{
				$menus[] = [
					'role_id' => $_POST['role_id'],
					'menu_id' => $row
				];
			}
		}
		else
		{
			$this->error('操作失败！');
		}
		
		$access = M('access');
		$access->startTrans();
		$access->where('role_id='.$_POST['role_id'])->delete();
		
		if($access->addAll($menus))
        {
			$access->commit(); // 提交事务
            $this->success('操作成功！');
        }
		else
		{
			$access->rollback(); // 事务回滚
			$this->error('操作失败！');
		}
    }
	
	//将栏目列表生成数组
	public function get_category($modelname,$pid=0,$pad=0)
	{
		$arr=array();
		
		$cats = M($modelname)->where("pid=$pid")->order('id asc')->select();
		
		if($cats)
		{
			foreach($cats as $row)//循环数组
			{
				$row['deep'] = $pad;
				if($child = $this->get_category($modelname,$row["id"],$pad+1))//如果子级不为空
				{
					$row['child'] = $child;
				}
				$arr[] = $row;
			}
			return $arr;
		}
	}

	public function category_tree($list,$pid=0)
	{
		global $temp;
		if(!empty($list))
		{
			foreach($list as $v)
			{
				$temp[] = array("id"=>$v['id'],"deep"=>$v['deep'],"name"=>$v['name'],"pid"=>$v['pid']);
				//echo $v['id'];
				if(array_key_exists("child",$v))
				{
					$this->category_tree($v['child'],$v['pid']);
				}
			}
		}
		
		return $temp;
	}
}
