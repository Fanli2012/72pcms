<?php
namespace Fladmin\Controller;
use Fladmin\Controller\BaseController;

class CategoryController extends BaseController
{
    public function index()
    {
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
                $this->postone = M("Arctype")->field('content',true)->where("id=$id")->find();
            }
            $this->id = $id;
        }
        else
        {
            $this->id = 0;
        }
        
        $this->display();
    }
    
    public function doadd()
    {
        if(!empty($_POST["typename"])){$data['typename'] = $_POST["typename"];}else {$data['typename']="";}//栏目名称
        if(!empty($_POST["prid"])){if($_POST["prid"]=="top"){$data['reid']=0;}else{$data['reid'] = $_POST["prid"];}}//父级栏目id
        if(!empty($_POST["typedir"])){$data['typedir'] = $_POST["typedir"];}else {$data['typedir']="";}//栏目别名
        if(!empty($_POST["templist"])){$data['templist'] = $_POST["templist"];}else {$data['templist']="";}//列表模板
        if(!empty($_POST["temparticle"])){$data['temparticle'] = $_POST["temparticle"];}else {$data['temparticle']="";}//文章模版
        if(!empty($_POST["seotitle"])){$data['seotitle'] = $_POST["seotitle"];}else {$data['seotitle']="";}//seotitle
        if(!empty($_POST["keywords"])){$data['keywords'] = $_POST["keywords"];}else {$data['keywords']="";}//关键词
        if(!empty($_POST["description"])){$data['description'] = $_POST["description"];}else {$data['description']="";}//栏目描述
        if(!empty($_POST["content"])){$data['content'] = $_POST["content"];}else {$data['content']="";}//栏目内容
        if(!empty($_POST["litpic"])){$data['litpic'] = $_POST["litpic"];}else {$data['litpic']="";}//缩略图
        if(!empty($_POST["seokeyword"])){$data['seokeyword'] = $_POST["seokeyword"];}else {$data['seokeyword']="";}//seokeyword
        $data['addtime'] = time();//添加时间
        
		if(M('Arctype')->data($data)->add())
        {
            $this->success('添加成功！', U('Category/index'), 1);
        }
		else
		{
			$this->error('添加失败！请修改后重新添加', U('Category/index'), 3);
		}
    }
    
    public function edit()
    {
        $id = $_GET["id"];if(preg_match('/[0-9]*/',$id)){}else{exit;}
        
        $Arctype = M('Arctype');
        
        $this->id = $id;
        $post = $Arctype->where("id=$id")->find();
        $reid = $post['reid'];
        if($reid!=0){$this->postone = $Arctype->where("id=$reid")->find();}
        $this->post = $post;
        
        $this->display();
    }
    
    public function doedit()
    {
        if(!empty($_POST["id"])){$id = $_POST["id"];}else {$id="";exit;}
        if(!empty($_POST["typename"])){$data['typename'] = $_POST["typename"];}else {$data['typename']="";}//栏目名称
        if(!empty($_POST["typedir"])){$data['typedir'] = $_POST["typedir"];}else {$data['typedir']="";}//栏目别名
        if(!empty($_POST["templist"])){$data['templist'] = $_POST["templist"];}else {$data['templist']="";}//列表模板
        if(!empty($_POST["temparticle"])){$data['temparticle'] = $_POST["temparticle"];}else {$data['temparticle']="";}//文章模版
        if(!empty($_POST["seotitle"])){$data['seotitle'] = $_POST["seotitle"];}else {$data['seotitle']="";}//seotitle
        if(!empty($_POST["keywords"])){$data['keywords'] = $_POST["keywords"];}else {$data['keywords']="";}//关键词
        if(!empty($_POST["description"])){$data['description'] = $_POST["description"];}else {$data['description']="";}//栏目描述
        if(!empty($_POST["content"])){$data['content'] = $_POST["content"];}else {$data['content']="";}//栏目内容
        if(!empty($_POST["litpic"])){$data['litpic'] = $_POST["litpic"];}else {$data['litpic']="";}//缩略图
        if(!empty($_POST["seokeyword"])){$data['seokeyword'] = $_POST["seokeyword"];}else {$data['seokeyword']="";}//seokeyword
        $data['addtime'] = time();//添加时间
        
		if(M('Arctype')->where("id=$id")->save($data))
        {
            $this->success('修改成功！', U('Category/index'), 1);
        }
		else
		{
			$this->error('修改失败！请修改后重新添加', U('Category/edit',array('id'=>$_POST["id"])), 3);
		}
    }
    
    public function del()
    {
		if(!empty($_GET["id"])){$id = $_GET["id"];}else{$this->error('删除失败！请重新提交', U('Category/index'), 3);}if(preg_match('/[0-9]*/',$id)){}else{exit;}
		
		$Arctype = M('Arctype');
		
		if($Arctype->where("reid=$id")->find())
		{
			$this->error('删除失败！请先删除子栏目', U('Category/index'), 3);
		}
		else
		{
			$Arctype->startTrans(); // 启动事务
			
			if($Arctype->where("id=$id")->delete())
			{
				$Article=M("Article");
				
				if($Article->where("typeid=$id")->count()>0) //判断该分类下是否有文章，如果有把该分类下的文章也一起删除
				{
					if($Article->where("typeid=$id")->delete())
					{
						$Arctype->commit(); // 提交事务
						$this->success('删除成功', U('Category/index'), 1);
					}
					else
					{
						$Arctype->rollback(); // 事务回滚
						$this->error('栏目下的文章删除失败！', U('Category/index'), 3);
					}
				}
				else
				{
					$Arctype->commit(); // 提交事务
					$this->success('删除成功', U('Category/index'), 1);
				}
			}
			else
			{
				$Arctype->rollback(); // 事务回滚
				$this->error('删除失败！请重新提交', U('Category/index'), 3);
			}
		}
    }
}
