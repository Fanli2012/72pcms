<?php
namespace Fladmin\Controller;
use Fladmin\Controller\BaseController;

class TestController extends BaseController
{
    public function index()
    {
        echo md5('admin');
    }
    
}
