<?php

namespace Mpx\StdLogger\Logger\Handler;

use Monolog\Logger;

class Exception extends \Mpx\StdLogger\Logger\Handler\Base
{
    /**
     * @var string
     */
    protected $fileName = '/var/log/exception.log';

    /**
     * @var int
     */
    protected $loggerType = Logger::INFO;
}
