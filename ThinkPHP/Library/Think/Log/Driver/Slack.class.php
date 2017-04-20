<?php
// +----------------------------------------------------------------------
// | TOPThink [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://topthink.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: luofei614 <weibo.com/luofei614>
// +----------------------------------------------------------------------

namespace Think\Log\Driver;

class Slack {
    private $webhooks = 'https://hooks.slack.com/services/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';
    private $_room = 'lemon-exception';
    public function write($log, $room = '')
    {
        $room = ($room) ? $room : $this->_room;
        $data = "payload=" . json_encode([
                "channel"       =>  "#{$room}",
                "text"          =>  $log
        ]);

        $ch = curl_init($this->webhooks);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}