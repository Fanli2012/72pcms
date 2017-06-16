<?php
namespace Api\Controller;
use Api\Controller\BaseController;

class IndexController extends BaseController
{
    public function index()
	{
        //echo "<pre>";
        //print_r(get_pre_next(array('aid'=>3,'typeid'=>2,'type'=>"pre")));
        $this->display();
    }
	
	public function listarc()
	{
		$page=$_GET["page"];
		$pagesize=$_GET["pagesize"];
		$start=$page*$pagesize;
		
		if(!empty($_GET['tuijian'])){$tuijian=$_GET['tuijian'];}
		if(!empty($_GET['typeid'])){$typeid=$_GET['typeid'];}
		if(!empty($_GET['image'])){$image=1;}
	
		if($page<=10)
		{
			$posts=arclist(array("tuijian"=>$tuijian,"typeid"=>$typeid,"image"=>$image,"limit"=>"$start,$pagesize"));
		}
		
		echo json_encode($posts);
    }
	
	public function qrcode()
	{
		$url = $_REQUEST['url'];
		
		$url = str_replace("%26","&",$url);
		$url = str_replace("%3F","?",$url);
		$url = str_replace("%3D","=",$url);
		
		include dirname(dirname(__FILE__))."/ORG/phpqrcode/qrlib.php";
		return QRcode::png($url,false,"H",6);
	}
}
