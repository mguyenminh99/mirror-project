<?php

namespace Mpx\Marketplace\Observer\Order\Invoice;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Controller\Result\ForwardFactory;

/**
 * Redirect router 404
 * Class NoRoute
 */
class NoRoute implements ObserverInterface
{
    private const DEPLOY_MODE_CODE_PRODUCTION = "production";

    /**
     * @var ForwardFactory
     */
    protected $forwardFactory;

    /**
     * @var \Magento\Framework\App\State
     */
    protected $_appState;

    /**
     * @param ForwardFactory $forwardFactory
     * @param \Magento\Framework\App\State $appState
     */
    public function __construct(
        ForwardFactory $forwardFactory,
        \Magento\Framework\App\State $appState
    ) {
        $this->forwardFactory = $forwardFactory;
        $this->_appState = $appState;
    }

    /**
     * Forward page 404
     *
     * @param Observer $observer
     * @return \Magento\Framework\Controller\Result\Forward|void
     */
    public function execute(Observer $observer)
    {
        if ($this->_appState->getMode() == self::DEPLOY_MODE_CODE_PRODUCTION) {
            $resultForward = $this->forwardFactory->create();
            $resultForward->setController('norouter');
            $resultForward->forward('noroute');
            return $resultForward;
        }
    }
}
