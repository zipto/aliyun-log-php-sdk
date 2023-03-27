<?php

namespace Zipto\AliyunLogSdk;

use Zipto\AliyunLogSdk\ProtoBuf\Log;
use Zipto\AliyunLogSdk\ProtoBuf\LogGroup;

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
        $this->logStore = $this->getValidLogStore($logStore);
    }

    /**
     * @param $logStore
     * @return string
     * logstore 规则验证
     */
    private function getValidLogStore($logStore){
        //只能包括小写字母、数字、短划线（-）和下划线（_）。不符合条件的字符会被替换成下划线（_）。
        //必须以小写字母或者数字开头和结尾。不符合条件的开头和结尾，替换为[prefix] 或 [suffix]。
        //长度为3-63字符。长度不足3，加上 prefix_ 前缀，太长的话，只保留前面的63个字符。
        $logStore = strtolower($logStore);
        $logStore = preg_replace('/[^a-z0-9_-]/','_',$logStore);
        $logStore = preg_replace('/^[^a-z0-9]/','prefix_',$logStore);
        $logStore = preg_replace('/[^a-z0-9]$/','_suffix',$logStore);
        $logStore = strlen($logStore) < 3 ? 'prefix_' . $logStore : $logStore;
        $logStore = substr($logStore,0,63);
        return $logStore;
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
    public function log($message, $topic = '' , $level = 'info'){
        $client = new \GuzzleHttp\Client();
        $group = new LogGroup();
        $group->setTopic($topic);
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
            if (is_array($data) && $data['errorCode'] === 'LogStoreNotExist'){
                if ($this->createLogStore()){
                    $this->createIndex();
                    $this->log($message,$topic,$level);
                }else{
                    throw new \Exception("Create LogStore Failed");
                }
            }else{
                $message = "Aliyun Status : " .$response->getStatusCode() . " Response:". $response->getBody()->getContents();
                throw new \Exception($message);
            }
        }
    }

    public function info($message,$topic = ''){
        $this->log($message,$topic, 'info');
    }

    public function warning($message,$topic = ''){
        $this->log($message,$topic,'warning');
    }

    public function error($message, $topic = ''){
        $this->log($message,$topic,'error');
    }

    public function debug($message,$topic = ''){
        $this->log($message,$topic,'debug');
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
            $data = json_decode($response->getBody()->getContents(), true);
            if (is_array($data) && $data['errorCode'] === 'LogStoreAlreadyExist'){
                return true;
            }
            throw new \Exception($response->getBody()->getContents());
        }
        return true;
    }

    private function createIndex(){
        $client = new \GuzzleHttp\Client();
        $header = [
            'Content-Type' => 'application/json',
        ];
        $path = sprintf(Config::API_CREATE_INDEX,$this->logStore);
        $uri = $this->getHost() . $path;
        $body = [
            'log_reduce' => Config::LOG_INDEX_REDUCE,
            'ttl' => Config::LOG_TTL,
            'line' => [
                'chn' => true,
                'token' => Config::LOG_INDEX_TOKEN,
            ]
        ];
        $signedHeader = Util::sign('POST', $path, $this->accessKeyId, $this->accessKeySecret, [], $header, json_encode($body));

        $response = $client->request('POST', $uri, [
            'headers' => $signedHeader,
            'body' => json_encode($body),
            'http_errors' => false,
        ]);
        if ($response->getStatusCode() != 200) {
            $data = json_decode($response->getBody()->getContents(), true);
            if (is_array($data) && $data['errorCode'] === 'IndexAlreadyExist'){
                return true;
            }
            throw new \Exception($response->getBody()->getContents());
        }
        sleep(Config::CREATE_LOG_INDEX_WAIT);
        return true;
    }


}