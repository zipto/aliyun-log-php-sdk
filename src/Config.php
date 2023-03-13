<?php

namespace Wuhuaji\AliyunLogSdk;

class Config
{
    const CREATE_LOG_STORE_WAIT = 5;

    const LOG_SHARD_COUNT = 2;

    const LOG_TTL = 3560;

    const API_CREATE_LOG_STORE = '/logstores';

    const API_PUT_LOGS = "/logstores/%s/shards/lb";

}