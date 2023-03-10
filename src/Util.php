<?php

namespace Wuhuaji\AliyunLogSdk;

class Util
{

    /**
     * @param $method
     * @param $uri
     * @param $accessKeyId
     * @param $accessKeySecret
     * @param $params
     * @param $headers
     * @param $body
     * @return array
     */
    public static function sign($method, $uri, $accessKeyId, $accessKeySecret, $params, array $headers, $body)
    {
        $contentLength = 0;
        $headers["x-log-apiversion"] = "0.6.0";
        $headers["x-log-signaturemethod"] = "hmac-sha1";
        if (!is_null($body) && strlen($body) > 0) {
            $contentLength = strlen($body);
            $contentMd5 = strtoupper(md5($body));
            $headers["Content-MD5"] = $contentMd5;
        }
        // date
        setLocale(LC_TIME, 'en_US');
        $date = gmdate('D, d M Y H:i:s \G\M\T', time());
        $headers["Date"] = $date;
        $headers["Content-Length"] = (string)$contentLength;
        $contentType = isset($headers['Content-Type']) ? $headers['Content-Type'] : '';
        $message = $method . "\n" . $contentMd5 . "\n" . $contentType . "\n" . $date . "\n";
        // header
        $filterHeaders = [];
        foreach ($headers as $key => $val) {
            if (str_starts_with($key, 'x-log-') || str_starts_with($key, 'x-acs-')) {
                $filterHeaders[$key] = $val;
            }
        }
        ksort($filterHeaders);
        foreach ($filterHeaders as $key => $val) {
            $message .= $key . ':' . $val . "\n";
        }
        // uri and params
        $message .= $uri;
        if (count($params) > 0) {
            $message .= '?';
        }
        ksort($params);
        $sep = '';
        foreach ($params as $key => $val) {
            $message .= $sep . $key . '=' . $val;
            $sep = '&';
        }
        // signature & authorization
        $signature = base64_encode(hash_hmac('sha1', $message, $accessKeySecret, TRUE));
        $auth = 'LOG ' . $accessKeyId . ':' . $signature;
        $headers['Authorization'] = $auth;
        return $headers;
    }


    public static function toBytes($logGroup) {
        $mem = fopen("php://memory", "rwb");
        $logGroup->write($mem);
        rewind($mem);
        $bytes="";

        if(feof($mem)===false){
            $bytes = fread($mem, 10*1024*1024);
        }
        fclose($mem);

        return $bytes;
    }

    public static function toByte(string $msg){
        $mem = fopen("php://memory", "rwb");
        fwrite($mem, $msg);
        rewind($mem);
        $bytes="";

        if(feof($mem)===false){
            $bytes = fread($mem, 10*1024*1024);
        }
        fclose($mem);

        return $bytes;
    }
}