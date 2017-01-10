<?php
namespace NN\Service;

use NN\Interfaces\SmsInterface; 
use NN\Logic\ValidateLogic;

class AxjSmsService implements SmsInterface
{
    private $sign     = '【车碰网】';
    private $param    = array();

    const   MAX_GET_NUM = 100;
    const   MAX_POST_NUM = 200;
    private $apiUrl = 'http://111.13.56.193:9007/axj_http_server/sms';

    public function __construct()
    {
        $this->param  = [
            'name' => C('SMS_USER'), 
            'pass' => C('SMS_KEY'), 
            'subid' => '']; 
    }

    public function send($phones, $content)
    {
        if (empty($content) || empty($phones)) {
            throw new \Exception('手机号码和内容不能为空');
        }
        if (is_string($phones)) {
            $phones = [$phones];
        }
        $phones = array_filter($phones, array(new ValidateLogic, 'validPhoneNum'));
        if (empty($phones)) {
            throw new \Exception('所有手机号码不为合法手机号码');
        }
        $totalPhoneNums = count($phones);
        if (strpos($content, '验证码') === false) {
            $content .= ' 退订回复TD';
        }
        $content = $content . $this->sign;
        $this->param['content'] = $content;
        $this->param['sendtime'] = date('YmdHis');
        if ($totalPhoneNums <= self::MAX_POST_NUM) {
            $this->param['mobiles'] = implode('|', $phones);
            $strParam = $this->genParamString($this->param);
            if ($totalPhoneNums >= self::MAX_GET_NUM) {
                $response = Http::post($this->apiUrl, $strParam); 
            }              
            else
            {
                $response = Http::get($this->apiUrl . '?' . $strParam, 60);
            }
        } else if (self::MAX_POST_NUM < $totalPhoneNums ) {
            $phoneChunks = array_chunk($phones, self::MAX_POST_NUM);
            foreach($phoneChunks as $v)
            {
                $this->param['sendtime'] = date('YmdHis', time());
                $this->param['mobiles'] = implode('|', $v);
                $strParam = $this->genParamString($this->param);
                $response = Http::post($this->apiUrl,  $strParam);         
            }
        }
        if (!empty($response)) {
            if (substr(rtrim($response), -2) == '00') {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    /**
     *    需要对特殊字符编码,否则发送会报错
     *    
     *    @param array $param
     *
     *    @return string
     **/
    private function genParamString($param)
    {
        $strParam = '';
        foreach($param as $k => $v)
        {
            $strParam .= '&' . $k . '=' . urlencode($v);
        }
        return substr($strParam, 1);
    }
}
