<?php

namespace Mpx\StdLogger\Logger\Handler;

use Magento\Framework\Filesystem\DriverInterface;
use Monolog\Formatter\LineFormatter;
use Monolog\Logger;

/**
 * Base stream handler
 */
class Base extends \Mpx\StdLogger\Logger\StreamHandlerStd
{
    const STDOUT_LOGGER = 'stdout_logger';
    const STDERR_LOGGER = 'stderr_logger';

    /**
     * @var string
     */
    protected $fileName;

    /**
     * @var int
     */
    protected $loggerType = Logger::DEBUG;

    /**
     * @var DriverInterface
     */
    protected $filesystem;

    protected $stdLoggerType;

    /**
     * @param DriverInterface $filesystem
     * @param string $filePath
     * @param string $fileName
     * @throws \Exception
     */
    public function __construct(
        DriverInterface $filesystem,
                        $filePath = null,
                        $fileName = null
    ) {
        $this->filesystem = $filesystem;
        if (!empty($fileName)) {
            $this->fileName = $this->sanitizeFileName($fileName);
        }
        parent::__construct(
            $filePath ? $filePath . $this->fileName : BP . DIRECTORY_SEPARATOR . $this->fileName,
            $this->loggerType
        );

        $this->setFormatter(new LineFormatter(null, null, true));
    }

    /**
     * Remove dots from file name
     *
     * @param string $fileName
     * @return string
     * @throws \InvalidArgumentException
     */
    private function sanitizeFileName($fileName)
    {
        if (!is_string($fileName)) {
            throw  new \InvalidArgumentException('Filename expected to be a string');
        }

        $parts = explode('/', $fileName);
        $parts = array_filter($parts, function ($value) {
            return !in_array($value, ['', '.', '..']);
        });

        return implode('/', $parts);
    }

    /**
     * @inheritDoc
     */
    public function write(array $record)
    {
        $logDir = $this->filesystem->getParentDirectory($this->url);
        if (!$this->filesystem->isDirectory($logDir)) {
            $this->filesystem->createDirectory($logDir);
        }

        parent::write($record);
    }

    /**
     * @return bool
     */
    public function setStdLoggerType($stdLoggerType)
    {
        return $this->stdLoggerType = $stdLoggerType;
    }
}
