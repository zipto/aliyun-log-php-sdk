<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: log.proto

namespace GPBMetadata;

class Log
{
    public static $is_initialized = false;

    public static function initOnce() {
        $pool = \Google\Protobuf\Internal\DescriptorPool::getGeneratedPool();

        if (static::$is_initialized == true) {
          return;
        }
        $pool->internalAddGeneratedFile(
            '
�
	log.protoZipto.AliyunLogSdk.ProtoBuf"v
Log
Time (:
Contents (2(.Zipto.AliyunLogSdk.ProtoBuf.Log.Content%
Content
Key (	
Value (	"$
LogTag
Key (	
Value (	"�
LogGroup.
Logs (2 .Zipto.AliyunLogSdk.ProtoBuf.Log
Reserved (	H �
Topic (	H�
Source (	H�4
LogTags (2#.Zipto.AliyunLogSdk.ProtoBuf.LogTagB
	_ReservedB
_TopicB	
_Source"K
LogGroupList;
logGroupList (2%.Zipto.AliyunLogSdk.ProtoBuf.LogGroupbproto3'
        , true);

        static::$is_initialized = true;
    }
}

