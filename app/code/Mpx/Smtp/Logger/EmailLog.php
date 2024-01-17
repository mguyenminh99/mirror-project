<?php

namespace Mpx\Smtp\Logger;

use Magento\Framework\App\ObjectManager;

class EmailLog extends \Magento\Framework\Logger\Monolog
{
    /**
     * {@inheritdoc}
     */
    public function __construct($name, array $handlers = [], array $processors = [])
    {
        /**
         * TODO: This should be eliminated with MAGETWO-53989
         */
        $handlers = array_values($handlers);

        parent::__construct($name, $handlers, $processors);
    }

    /**
     * Adds a log record.
     *
     * @param integer $level The logging level
     * @param string $message The log message
     * @param array $context The log context
     * @return Boolean Whether the record has been processed
     */
    public function addRecord($level, $message, array $context = [])
    {
        /**
         * To preserve compatibility with Exception messages.
         * And support PSR-3 context standard.
         *
         * @link http://www.php-fig.org/psr/psr-3/#context PSR-3 context standard
         */
        if ($message instanceof \Exception && !isset($context['exception'])) {
            $context['exception'] = $message;
        }

        $message = $message instanceof \Exception ? $message->getMessage() : $message;

        $this->sendEmailLog($level,$message);

        return parent::addRecord($level, $message, $context);
    }

    public function sendEmailLog($level,$message)
    {
        $objectManager = ObjectManager::getInstance();
        $emailLog = $objectManager->create(\Mpx\Smtp\Helper\SendEmailLog::class);
        $errorLogCode = [\Monolog\Logger::CRITICAL,\Monolog\Logger::ERROR];
        $currentTime = (new \Datetime('now', new \DateTimeZone('Asia/Tokyo')))->format('Y-m-d h:i:s');

        if (!in_array($level, $errorLogCode)) {
            return;
        }

        $message = $currentTime . PHP_EOL . getenv('HOST_NAME') . PHP_EOL . $message;
        $subject = "[x-shopping-st] system " . (($level == \Monolog\Logger::CRITICAL) ? "CRITICAL" : "ERROR");
        $emailLog->sendEmail($message, $subject);
    }
}
