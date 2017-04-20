<?php
namespace NN\Lib;

/**
 *  Http请求类，实现了get post请求
 *
 *  @author chanceJaw <zhouhongchang@3ncto.com>
 **/
class Http {
    /**
     *  实现了用curl发起get请求
     *
     *  @param string   $url     请求的url
     *  @param interger $timeout 请求超时
     *  @param array    $header  请求的header
     *
     *  @return array|exception
     **/
    public static function get($url, $timeout = 1, $header = null)
    {
        $handler = curl_init();
        $options = [
            CURLOPT_URL     => $url,
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_RETURNTRANSFER => true
        ];
        if (!is_null($header)) {
            $options =$options + $header;
        }
        curl_setopt_array($handler, $options);
        $data = curl_exec($handler);
        $info = curl_getinfo($handler);
        curl_close($handler);
        if ($info['http_code'] == 200) {
            return $data;
        }
        return new \Exception("requested url:{$url} fail. response :".json_encode($info));
    }

    /**
     *  实现了用curl发起post请求
     *
     *  @param string   $url    请求的url
     *  @param interger $data   请求数据
     *  @param array    $header 请求的header
     *
     *  @return array|exception
     **/
    public static function post($url, $data, $header = null)
    {
        $handler = curl_init();
        $options = [
            CURLOPT_URL            => $url,
            CURLOPT_TIMEOUT        => 60,
            CURLOPT_POSTFIELDS     => $data,
            CURLOPT_RETURNTRANSFER => true
        ];
        if (!is_null($header)) {
            $options =$options + $header;
        }

        curl_setopt_array($handler, $options);
        $data = curl_exec($handler);
        curl_close($handler);
        return $data;
    }

    public static function multi($urlarr)
    {
        $result = $res = $handler = array();
        $nch = 0;
        $mh = curl_multi_init();
        foreach ($urlarr as $nk => $url) {
            $timeout  = 2;
            $handler[$nch] = curl_init();
            curl_setopt_array($handler[$nch], [
                CURLOPT_URL => $url,
                CURLOPT_HEADER => false,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => $timeout,
            ]);
            curl_multi_add_handle($mh, $handler[$nch]);
            ++$nch;
        }
        /* wait for performing request */
        do {
            $mrc = curl_multi_exec($mh, $running);
        } while (CURLM_CALL_MULTI_PERFORM == $mrc);
        while ($running && $mrc == CURLM_OK) {
            while (curl_multi_exec($mh, $running) === CURLM_CALL_MULTI_PERFORM);
            if (curl_multi_select($mh) != -1) {
                // pull in new data;
                do {
                    $mrc = curl_multi_exec($mh, $running);
                } while (CURLM_CALL_MULTI_PERFORM == $mrc);
            }
        }

        if ($mrc != CURLM_OK) {
            error_log("CURL Data Error");
        }

        /* get data */
        $nch = 0;
        foreach ($urlarr as $moudle => $node) {
            if (($err = curl_error($handler[$nch])) == '') {
                $res[$nch]=curl_multi_getcontent($handler[$nch]);
                $result[$moudle]=$res[$nch];
            }
            curl_multi_remove_handle($mh,$handler[$nch]);
            curl_close($handler[$nch]);
            ++$nch;
        }
        curl_multi_close($mh);
        return  $result;
    }
}
