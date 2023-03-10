<?php

namespace Wuhuaji\AliyunLogSdk;

use Wuhuaji\AliyunLogSdk\ProtoBuf\Log;
use Wuhuaji\AliyunLogSdk\ProtoBuf\LogGroup;

class Client
{
    protected $accessKeyId;
    protected $accessKeySecret;
    protected $endpoint;
    protected $project;

    public function __construct($accessKeyId, $accessKey, $endpoint, $project)
    {
        $this->accessKeyId = $accessKeyId;
        $this->accessKeySecret = $accessKey;
        $this->endpoint = $endpoint;
        $this->project = $project;
    }

    public function putLog($message){
        $client = new \GuzzleHttp\Client();
        
        $host = "http://" . $this->project . '.' . $this->endpoint;

        $string = Util::toByte($message);
        $header = [
            'x-log-compresstype' => 'deflate',
            'Content-Type' => 'application/x-protobuf',
            'x-log-bodyrawsize' => strlen($string)
        ];
        $body = gzcompress ( $string, 6 );
        $uri = $host . '/logstores/staging_logstore/shards/lb';
        var_dump($uri);
        $signedHeader = Util::sign('POST', '/logstores/staging_logstore/shards/lb', $this->accessKeyId, $this->accessKeySecret, [], $header, $body);
        $response = $client->request('POST', $host . "/logstores/staging_logstore/shards/lb", [
            'headers' => $signedHeader,
            'body' => $body
        ]);
        return $response;
    }

    public function test($message){
        $client = new \GuzzleHttp\Client();

        $host = "http://" . $this->project . '.' . $this->endpoint;

        $group = new LogGroup();
        $log = new Log();
        $log->setTime(time());
        $content = new Log\Content();
        $content->setKey('hello');
        $content->setValue('world');
        $log->setContents([$content]);
        $group->setLogs([$log]);
        $body = $group->serializeToString();

        $header = [
            'x-log-compresstype' => 'deflate',
            'Content-Type' => 'application/x-protobuf',
            'x-log-bodyrawsize' => strlen($body)
        ];
        $body = gzcompress ( $body, 6 );
        $uri = $host . '/logstores/staging_logstore/shards/lb';
        var_dump($uri);
        $signedHeader = Util::sign('POST', '/logstores/staging_logstore/shards/lb', $this->accessKeyId, $this->accessKeySecret, [], $header, $body);
        $response = $client->request('POST', $host . "/logstores/staging_logstore/shards/lb", [
            'headers' => $signedHeader,
            'body' => $body
        ]);
        return $response;
    }


}