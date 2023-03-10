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

    private function getContent($key,$value){
        $content = new Log\Content();
        $content->setKey($key);
        $content->setValue($value);
        return $content;
    }

    public function log($message,$level = 'info'){
        $client = new \GuzzleHttp\Client();
        $host = "http://" . $this->project . '.' . $this->endpoint;

        $group = new LogGroup();
        $log = new Log();
        $log->setTime(time());

        $log->setContents([$this->getContent('level',$level),$this->getContent('message',$message)]);
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