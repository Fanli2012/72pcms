<?php
namespace Fladmin\Controller;
use Fladmin\Controller\BaseController;

class ProductTypeController extends BaseController
{
	function _initialize()
	{
		parent::_initialize();
    }
	
    public function index()
    {
		$this->catlist = tree(get_category('ProductType',0));
		
        $this->display();
    }
    
    public function add()
    {
        if(!empty($_GET["reid"]))
        {
            $id = $_GET["reid"];
            if(preg_match('/[0-9]*/',$id)){}else{exit;}
            if($id!=0)
            {
                $this->postone = M("ProductType")->field('content',true)->where("id=$id")->find();
            }
            $this->id = $id;
        }
        else
        {
            $this->id = 0;
        }
		
        $this->display('edit');
    }
    
    public function doadd()
    {
        if(!empty($_POST["prid"])){if($_POST["prid"]=="top"){$_POST['reid']=0;}else{$_POST['reid'] = $_POST["prid"];}}//父级栏目id
        $_POST['addtime'] = time();//添加时间
        
		if(M('ProductType')->add($_POST))
        {
            $this->success('添加成功！', '/'.FLADMIN.'/ProductType' , 1);
        }
		else
		{
			$this->error('添加失败！请修改后重新添加', '/'.FLADMIN.'/ProductType' , 3);
		}
    }
    
    public function edit()
    {
        $id = $_GET["id"];if(preg_match('/[0-9]*/',$id)){}else{exit;}
        
        $ProductType = M('ProductType');
        
        $this->id = $id;
        $post = $ProductType->where("id=$id")->find();
        $reid = $post['reid'];
        if($reid!=0){$this->postone = $ProductType->where("id=$reid")->find();}
        $this->post = $post;
        
        $this->display();
    }
    
    public function doedit()
    {
        if(!empty($_POST["id"])){$id = $_POST["id"];unset($_POST["id"]);}else{$id="";exit;}
        $_POST['addtime'] = time();//添加时间
        
		if(M('ProductType')->where("id=$id")->save($_POST))
        {
            $this->success('修改成功！', '/'.FLADMIN.'/ProductType' , 1);
        }
		else
		{
			$this->error('修改失败！请修改后重新添加', '/'.FLADMIN.'/ProductType/edit?id='.$_POST["id"] , 3);
		}
    }
    
    public function del()
    {
		if(!empty($_GET["id"])){$id = $_GET["id"];}else{$this->error('删除失败！请重新提交','/'.FLADMIN.'/ProductType' , 3);}if(preg_match('/[0-9]*/',$id)){}else{exit;}
		
		$ProductType = M('ProductType');
		
		if($ProductType->where("reid=$id")->find())
		{
			$this->error('删除失败！请先删除子分类', '/'.FLADMIN.'/ProductType', 3);
		}
		else
		{
			$ProductType->startTrans(); // 启动事务
			
			if($ProductType->where("id=$id")->delete())
			{
				$Product=M("Product");
				
				if($Product->where("typeid=$id")->count()>0) //判断该分类下是否有商品，如果有把该分类下的商品也一起删除
				{
					if($Product->where("typeid=$id")->delete())
					{
						$ProductType->commit(); // 提交事务
						$this->success('删除成功', '/'.FLADMIN.'/ProductType' , 1);
					}
					else
					{
						$ProductType->rollback(); // 事务回滚
						$this->error('分类下的商品删除失败！', '/'.FLADMIN.'/ProductType', 3);
					}
				}
				else
				{
					$ProductType->commit(); // 提交事务
					$this->success('删除成功', '/'.FLADMIN.'/ProductType' , 1);
				}
			}
			else
			{
				$ProductType->rollback(); // 事务回滚
				$this->error('删除失败！请重新提交', '/'.FLADMIN.'/ProductType', 3);
			}
		}
    }
}
