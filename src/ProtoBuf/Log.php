<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: log.proto

namespace Zipto\AliyunLogSdk\ProtoBuf;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>Zipto.AliyunLogSdk.ProtoBuf.Log</code>
 */
class Log extends \Google\Protobuf\Internal\Message
{
    /**
     * UNIX Time Format
     *
     * Generated from protobuf field <code>uint32 Time = 1;</code>
     */
    protected $Time = 0;
    /**
     * Generated from protobuf field <code>repeated .Zipto.AliyunLogSdk.ProtoBuf.Log.Content Contents = 2;</code>
     */
    private $Contents;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type int $Time
     *           UNIX Time Format
     *     @type array<\Zipto\AliyunLogSdk\ProtoBuf\Log\Content>|\Google\Protobuf\Internal\RepeatedField $Contents
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Log::initOnce();
        parent::__construct($data);
    }

    /**
     * UNIX Time Format
     *
     * Generated from protobuf field <code>uint32 Time = 1;</code>
     * @return int
     */
    public function getTime()
    {
        return $this->Time;
    }

    /**
     * UNIX Time Format
     *
     * Generated from protobuf field <code>uint32 Time = 1;</code>
     * @param int $var
     * @return $this
     */
    public function setTime($var)
    {
        GPBUtil::checkUint32($var);
        $this->Time = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>repeated .Zipto.AliyunLogSdk.ProtoBuf.Log.Content Contents = 2;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getContents()
    {
        return $this->Contents;
    }

    /**
     * Generated from protobuf field <code>repeated .Zipto.AliyunLogSdk.ProtoBuf.Log.Content Contents = 2;</code>
     * @param array<\Zipto\AliyunLogSdk\ProtoBuf\Log\Content>|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setContents($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \Zipto\AliyunLogSdk\ProtoBuf\Log\Content::class);
        $this->Contents = $arr;

        return $this;
    }

}

