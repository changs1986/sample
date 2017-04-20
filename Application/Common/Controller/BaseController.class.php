<?php
namespace Common\Controller;

use NN\Util\IoC;

class BaseController extends \Think\Controller
{
    public function __construct()
    {
        parent::__construct();
        IoC::Init();
    }
}
