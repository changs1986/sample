<?php
/**
 * Created by PhpStorm.
 * User: Kongho
 * Date: 15/8/5
 */

/**
 * 创建Api返回数据数组
 *
 * @param $ret_code
 * @param array $data
 * @param string $request_args
 * @param string $customDesc
 * @return array
 */
function createApiResponse ($ret_code, $data = array(), $customDesc = '') {
    $ret_msg = '';
    $description = '';

    switch ($ret_code) {
        case '200':
            $ret_msg = 'OK';
            $description = '成功';
            break;

        case '201':
            $ret_msg = 'Created';
            $description = '成功创建';
            break;

        case '202':
            $ret_msg = 'Accepted';
            $description = '请求已进入后台排队';
            break;

        case '204':
            $ret_msg = 'No Content';
            $description = '删除数据成功';
            break;

        case '400':
            $ret_msg = 'Invalid Request';
            $description = '请求格式错误';
            break;

        case '401':
            $ret_msg = 'Unauthorized';
            $description = '未授权';
            break;

        case '403':
            $ret_msg = 'Forbidden';
            $description = '鉴权成功，但是该用户没有权限';
            break;

        case '404':
            $ret_msg = 'Not Found';
            $description = '请求的资源不存在';
            break;

        case '405':
            $ret_msg = 'Method Not Allowed';
            $description = '该http方法不被允许';
            break;

        case '406':
            $ret_msg = 'Not Acceptable';
            $description = '请求的格式不可得';
            break;

        case '410':
            $ret_msg = 'Gone';
            $description = '该url对应的资源现在不可用';
            break;

        case '415':
            $ret_msg = 'Unsupported Media Type';
            $description = '请求类型错误';
            break;

        case '422':
            $ret_msg = 'Unprocesable Entity';
            $description = '创建对象时发生一个验证错误';
            break;

        case '429':
            $ret_msg = 'Too Many Request';
            $description = '请求过多';
            break;

        case '500':
            $ret_msg = 'Internal Server Error';
            $description = '服务器内部访问出错';
            break;
    }

    $result = array();
    $result['ret_code'] = $ret_code;
    $result['ret_msg'] = $ret_msg;
    $result['description'] = $customDesc ? $customDesc : $description;
    $result['data'] = is_null($data) ? array() : $data;

    if ($ret_code != 200) {
        $result['request_args'] = IS_POST ? $_POST : $_GET;
    }
    header('HTTP/1.1 ' . $ret_code . ' ' . $ret_msg);
    header('content-type:application/json;charset=utf-8');
    exit(json_encode($result));
    //return $result;
}

