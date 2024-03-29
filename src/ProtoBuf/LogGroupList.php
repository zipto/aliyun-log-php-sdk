<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: log.proto

namespace Zipto\AliyunLogSdk\ProtoBuf;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>Zipto.AliyunLogSdk.ProtoBuf.LogGroupList</code>
 */
class LogGroupList extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>repeated .Zipto.AliyunLogSdk.ProtoBuf.LogGroup logGroupList = 1;</code>
     */
    private $logGroupList;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type array<\Zipto\AliyunLogSdk\ProtoBuf\LogGroup>|\Google\Protobuf\Internal\RepeatedField $logGroupList
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Log::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>repeated .Zipto.AliyunLogSdk.ProtoBuf.LogGroup logGroupList = 1;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getLogGroupList()
    {
        return $this->logGroupList;
    }

    /**
     * Generated from protobuf field <code>repeated .Zipto.AliyunLogSdk.ProtoBuf.LogGroup logGroupList = 1;</code>
     * @param array<\Zipto\AliyunLogSdk\ProtoBuf\LogGroup>|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setLogGroupList($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \Zipto\AliyunLogSdk\ProtoBuf\LogGroup::class);
        $this->logGroupList = $arr;

        return $this;
    }

}

