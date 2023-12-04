<?php

namespace Mpx\StdLogger\Logger\Handler;

use Monolog\Logger;

class Debug extends \Mpx\StdLogger\Logger\Handler\Base
{
    /**
     * @var string
     */
    protected $fileName = '/var/log/debug.log';

    /**
     * @var int
     */
    protected $loggerType = Logger::DEBUG;
}
