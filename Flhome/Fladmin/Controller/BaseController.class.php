<?php
namespace Fladmin\Controller;
use Fladmin\Controller\CommonController;

class BaseController extends CommonController
{
    /**
     * 初始化
     * @param void
     * @return void
     */
	function _initialize()
	{
		parent::_initialize();
        
        //权限验证
        /* if($_SESSION['admin_user_info']['role_id'] <> 1)
        {
            $this->check();
        } */
    }
    /**
     * 获取分页数据及分页导航
     * @param string $modelname 模块名与数据库表名对应
     * @param array  $map       查询条件
     * @param string $orderby   查询排序
     * @param string $field     要返回数据的字段
     * @param int    $listRows  每页数量，默认10条
     * 
     * @return 格式化后输出的数据。内容格式为：
     *     - "code"                 (string)：代码
     *     - "info"                 (string)：信息提示
     * 
     *     - "result" array
     * 
     *     - "img_list"             (array) ：图片队列，默认8张
     *     - "img_title"            (string)：车图名称
     *     - "img_url"              (string)：车图片url地址
     *     - "car_name"             (string)：车名称
     */
    public function pageList($modelname, $map = '', $orderby = '',$field = '', $listRows = 15)
    {
        $model = M($modelname);
        
        //获取当前数据对象的【主键名称】
        $id = $model->getPk();
        $this->pkid = $id;
		
        if(isset($_REQUEST['order']))
        {
            $order = $_REQUEST['order'];
        }
        else
        {
            $order = !empty($orderby) ? $orderby : $id.' desc';
        }
        
        //取得满足条件的记录总数
		$count = $model->where($map)->count($id);
		
        if($count > 0)
        {
            //创建分页对象
            if (!empty ($_REQUEST['listRows']))
            {
                $listRows = $_REQUEST['listRows'];
            }
            
            $page = new \Think\Page($count, $listRows); // 实例化分页类 传入总记录数和每页显示的记录数
            //分页跳转的时候保证查询条件
            foreach($map as $key=>$val)
			{
				$Page->parameter[$key] = urlencode($val);
			}
			
            //$page->setConfig('header','个会员'); //分页样式定制
            $this->page = $page->show(); // 分页显示输出
			
            if($field=='')
			{
				$voList = $model->field('body',true)->where($map)->order($order)->limit($page->firstRow.','.$page->listRows)->select();
			}
			else
			{
				$voList = $model->field($field)->where($map)->order($order)->limit($page->firstRow.','.$page->listRows)->select();
			}
			
			//echo $model->getLastSql();
			//$this->voList = $voList;
        }
        
        return $voList;
    }
}