<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        $good = D('Common/Goods')->find(1);
        var_dump($good);
    }
}