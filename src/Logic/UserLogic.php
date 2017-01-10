<?php
namespace NN\Logic;

use Common\Model\UserModel;

class UserLogic 
{
    private $_instance;

    public function __construct()
    {
        $this->_instance = new UserModel;
    } 

    public function getList()
    {
        echo 'get list';
    }
}
