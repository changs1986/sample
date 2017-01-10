<?php
namespace Common\Model;

use Think\Model;

class UserModel extends BaseModel
{
    protected $trueTableName = "User";

    public static $status = [0 => '禁用', '正常'];

    public function hasUser($userName, $userId = 0)
    {
        $where = sprintf('phone="%s" and is_delete=0', $userName);
        if (0 < $userId) {
             $where .= sprintf(' and id != %d', $userId);
        }
        if ($this->where($where)->getField('id')) {
            return true;
        }
        return false;
    }

    /**
     * 促销活动中判断新用户的依据：没有下过订单或者订单都为已取消
     * 
     * @param interger $userId 用户id
     *
     * @return boolean
     **/
    public function userIsNew($userId)
    {
        if (D('Common/Order')->where('buyer_id=%d', $userId)->sum('status') <= 0) {
            return true;
        }
        return false;
    }
    /**
     * 给出用户名和密码添加新用户
     *
     * @param $username
     * @param $password
     * @return mixed
     */
    public function addUser($username, $password, $userCatId)
    {
        if ($this->hasUser($username))
        {
            return false;
        }
        load('Common.PasswordHash');
        $hashed = create_hash($password);
        $data = array(
            'name'     => $username,
            'phone'    => $username,
            'password' => $hashed['hashed_password'],
            'salt'     => $hashed['salt'],
            'created'  => time(),
            'status'   => 1,
            'updated'  => 0,
            'user_cat_id' => $userCatId,
        );
        return self::add($data);
    }

    public function getUserIdByName($name, $merchantName)
    {
        $userIds = array();

        if (!empty($name)) {
            $userIds = D('Common/User')->where('name like "%s"', '%'.$name.'%')->getField('id', true);
        }
        if (!empty($merchantName)) {
            $mUserIds = D('Common/Merchants')->where('name like "%s"', '%'.$merchantName.'%')->getField('user_id', true);
            if (!empty($userIds)) {
                $userIds = array_merge($userIds, $mUserIds);
            } else {
                $userIds = $mUserIds;
            }
        }
        if (!empty($userIds)) {
            return array_unique(array_filter($userIds));
        }
        return $userIds;
    }
}
