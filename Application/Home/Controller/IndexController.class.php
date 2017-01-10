<?php
namespace Home\Controller;

use Think\Controller;
use NN\Logic\UserLogic;
use NN\Service\AxjSmsService;

class IndexController extends Controller {
    public function index(){
        $logic = new UserLogic;
        $logic->getList();
        $s = new AxjSmsService;
        var_dump($s->send('1355656', '1245667'));
    }
}
