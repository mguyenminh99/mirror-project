<?php

namespace Mpx\StdLogger\Logger\Handler;

use Magento\Framework\Filesystem\DriverInterface;
use Monolog\Logger;
use Mpx\StdLogger\Logger\Handler\Base;

class Exception extends Base
{
    public function __construct(DriverInterface $filesystem, $filePath = null, $fileName = null)
    {
        $this->setStdLoggerType(Base::STDERR_LOGGER);
        parent::__construct($filesystem, $filePath, $fileName);
    }

    /**
     * @var string
     */
    protected $fileName = '/var/log/exception.log';

    /**
     * @var int
     */
    protected $loggerType = Logger::INFO;
}
