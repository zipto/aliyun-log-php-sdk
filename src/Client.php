<?php

namespace Wuhuaji\AliyunLogSdk;

use GuzzleHttp\Exception\GuzzleException;
use Wuhuaji\AliyunLogSdk\ProtoBuf\Log;
use Wuhuaji\AliyunLogSdk\ProtoBuf\LogGroup;

class Client
{
    protected $accessKeyId;
    protected $accessKeySecret;
    protected $endpoint;
    protected $project;

    protected $logStore;

    public function __construct($accessKeyId, $accessKey, $endpoint, $project, $logStore)
    {
        $this->accessKeyId = $accessKeyId;
        $this->accessKeySecret = $accessKey;
        $this->endpoint = $endpoint;
        $this->project = $project;
        $this->logStore = $logStore;
    }

    private function getContent($key,$value){
        $content = new Log\Content();
        $content->setKey($key);
        $content->setValue($value);
        return $content;
    }

    private function getHost(){
        return "http://" . $this->project . '.' . $this->endpoint;
    }

    /**
     * @param $message
     * @param $level
     * @return void
     * @throws \Exception
     */
    public function log($message,$level = 'info'){
        $client = new \GuzzleHttp\Client();
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
        $path = '/logstores/'. $this->logStore . '/shards/lb';
        $uri = $this->getHost() . $path;
        $signedHeader = Util::sign('POST', $path, $this->accessKeyId, $this->accessKeySecret, [], $header, $body);

        $response = $client->request('POST', $uri, [
            'headers' => $signedHeader,
            'body' => $body,
            'http_errors' => false,
        ]);
        if ($response->getStatusCode() != 200) {
            throw new \Exception($response->getBody()->getContents());
        }
    }

    public function info($message){
        $this->log($message,'info');
    }

    public function warning($message){
        $this->log($message,'warning');
    }

    public function error($message){
        $this->log($message,'error');
    }

    public function debug($message){
        $this->log($message,'debug');
    }
}