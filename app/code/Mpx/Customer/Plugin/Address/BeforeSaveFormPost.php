<?php
namespace Mpx\Customer\Plugin\Address;

use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Mpx\Customer\Helper\PostcodeValidation;
use Psr\Log\LoggerInterface;
use Exception;

class BeforeSaveFormPost
{
    /**
     * @var RedirectFactory
     */
    protected $redirectFactory;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var PostcodeValidation
     */
    protected $postcodeValidation;

    /**
     * @param RedirectFactory $redirectFactory
     * @param PostcodeValidation $postcodeValidation
     * @param LoggerInterface $logger
     */
    public function __construct(
        RedirectFactory        $redirectFactory,
        PostcodeValidation     $postcodeValidation,
        LoggerInterface        $logger
    ) {
        $this->redirectFactory = $redirectFactory;
        $this->postcodeValidation = $postcodeValidation;
        $this->logger = $logger;
    }

    /**
     * Function to run to validate post code.
     *
     * @param \Magento\Customer\Controller\Address\FormPost $subject
     * @param callable $process
     * @return Redirect
     */
    public function aroundExecute(
        \Magento\Customer\Controller\Address\FormPost $subject,
        callable $process
    ): Redirect {
        $params = $subject->getRequest()->getParams();
        $postCode = $params['postcode'];
        $error = $this->postcodeValidation->validatePostCode($params);
        $subject->getRequest()->setParam('postcode', str_replace("-", "", $postCode));

        if (!empty($error)) {
            try {
                $this->postcodeValidation->setErrorMessageToMessageManager($error);
                return $this->redirectFactory->create()->setPath('customer/address/edit');
            } catch (Exception $e) {
                $this->logger->critical($e->getMessage());
            }
        }
        return $process();
    }
}
