<?php
namespace Api\Controller;
use Think\Controller\RestController;

class GoodsController extends RestController {
    Public function rest() {
        switch ($this->_method){
        case 'get': // get请求处理代码
            break;
        case 'put': // put请求处理代码
            break;
        case 'delete':
            break;
        case 'post': // post请求处理代码
            break;
        }
    }
    
    Public function items()
    {
        $goods = D('Common/Goods')->limit(10)->field('id, goods_name, add_time')->select();
        createApiResponse(200, $goods);
    }
    
    public function item()
    {
        echo 'has item';
    }
    
    public function delete()
    {
        var_dump($_GET);
    }
    
    public function add()
    {
        var_dump($_POST);
    }
    public function up()
    {
        var_dump($_GET);
        echo 'update';
    }
}