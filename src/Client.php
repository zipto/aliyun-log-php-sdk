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
        $path = sprintf(Config::API_PUT_LOGS,$this->logStore);

        $uri = $this->getHost() . $path;
        $signedHeader = Util::sign('POST', $path, $this->accessKeyId, $this->accessKeySecret, [], $header, $body);

        $response = $client->request('POST', $uri, [
            'headers' => $signedHeader,
            'body' => $body,
            'http_errors' => false,
        ]);
        if ($response->getStatusCode() != 200) {
            $data = json_decode($response->getBody()->getContents(), true);
            // if the errorCode is LogStoreNotExist, create the LogStage first
            if ($data['errorCode'] === 'LogStoreNotExist'){
                var_dump($data);
                if ($this->createLogStore()){
                    $this->log($message,$level);
                }else{
                    throw new \Exception("Create LogStore Failed");
                }
            }else{
                    throw new \Exception($response->getBody()->getContents());
            }
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

    private function createLogStore()
    {
        $client = new \GuzzleHttp\Client();
        $header = [
            'Content-Type' => 'application/json',
        ];
        $uri = $this->getHost() . Config::API_CREATE_LOG_STORE;
        $body = [
            'logstoreName' => $this->logStore,
            'shardCount' => Config::LOG_SHARD_COUNT,
            'ttl' => Config::LOG_TTL
        ];
        $signedHeader = Util::sign('POST', Config::API_CREATE_LOG_STORE, $this->accessKeyId, $this->accessKeySecret, [], $header, json_encode($body));

        $response = $client->request('POST', $uri, [
            'headers' => $signedHeader,
            'body' => json_encode($body),
            'http_errors' => false,
        ]);
        if ($response->getStatusCode() != 200) {
            throw new \Exception($response->getBody()->getContents());
        }
        sleep(Config::CREATE_LOG_STORE_WAIT);
        return true;
    }
}