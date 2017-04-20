<?php
namespace NN\Logic;

use Common\Model\UserModel;

class UserLogic
{
    private $_instance;

    public function __construct(UserModel $user)
    {
        $this->_instance = new UserModel;
    }

    /**
     *  根据token获取用户id
     *
     *
     **/
    public static function getUid()
    {
        if (isset($_COOKIE['token'])) {
            $token = cookie('token');
        } else if (isset($_GET['token'])) {
            $token = cookie('token');
        }
        if (empty($token)) {
            return 0;
        }
        return D('Common/Session')->where('token="%s" and %d <= expired and user_type=0', $token, time())->getField('user_id');
    }

    public function getList()
    {
        echo 'get list';
    }
}
