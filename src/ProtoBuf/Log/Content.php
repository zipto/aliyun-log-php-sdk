<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: log.proto

namespace Zipto\AliyunLogSdk\ProtoBuf\Log;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>Zipto.AliyunLogSdk.ProtoBuf.Log.Content</code>
 */
class Content extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>string Key = 1;</code>
     */
    protected $Key = '';
    /**
     * Generated from protobuf field <code>string Value = 2;</code>
     */
    protected $Value = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $Key
     *     @type string $Value
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Log::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>string Key = 1;</code>
     * @return string
     */
    public function getKey()
    {
        return $this->Key;
    }

    /**
     * Generated from protobuf field <code>string Key = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setKey($var)
    {
        GPBUtil::checkString($var, True);
        $this->Key = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string Value = 2;</code>
     * @return string
     */
    public function getValue()
    {
        return $this->Value;
    }

    /**
     * Generated from protobuf field <code>string Value = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setValue($var)
    {
        GPBUtil::checkString($var, True);
        $this->Value = $var;

        return $this;
    }

}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Content::class, \Zipto\AliyunLogSdk\ProtoBuf\Log_Content::class);

