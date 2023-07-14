<?php
namespace XShoppingSt\MarketplaceBaseShipping\Logger;

class Handler extends \Magento\Framework\Logger\Handler\Base
{
    /**
     * Logging level
     * @var int
     */
    public $loggerType = Logger::INFO;

    /**
     * File name
     * @var string
     */
    public $fileName = '/var/log/mpbaseshipping.log';
}
