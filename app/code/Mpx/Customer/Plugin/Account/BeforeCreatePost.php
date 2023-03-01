<?php
namespace Mpx\Customer\Plugin\Account;

use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Mpx\Customer\Helper\CustomerInfoValidation;
use Psr\Log\LoggerInterface;
use Exception;

class BeforeCreatePost
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
     * @var CustomerInfoValidation
     */
    protected $customerInfoValidation;

    /**
     * @param RedirectFactory $redirectFactory
     * @param CustomerInfoValidation $customerInfoValidation
     * @param LoggerInterface $logger
     */
    public function __construct(
        RedirectFactory            $redirectFactory,
        CustomerInfoValidation     $customerInfoValidation,
        LoggerInterface            $logger
    ) {
        $this->redirectFactory = $redirectFactory;
        $this->customerInfoValidation = $customerInfoValidation;
        $this->logger = $logger;
    }

    /**
     * Function to run to validate customer information.
     *
     * @param \Magento\Customer\Controller\Account\CreatePost $subject
     * @param callable $process
     * @return Redirect
     */
    public function aroundExecute(
        \Magento\Customer\Controller\Account\CreatePost $subject,
        callable $process
    ): Redirect {
        $errors = [];
        $postCode = $subject->getRequest()->getParam('postcode');
        $phoneNumber = $subject->getRequest()->getParam('telephone');
        if ($postCode) {
            $errors = $this->customerInfoValidation->validatePostCode($postCode);
            $subject->getRequest()->setParam('postcode', str_replace("-", "", $postCode));
        }
        if ($phoneNumber) {
            $errors = $this->customerInfoValidation->validatePhoneNumber($phoneNumber);
        }

        if (!empty($errors)) {
            try {
                $this->customerInfoValidation->setErrorMessageToMessageManager($errors);
                return $this->redirectFactory->create()->setPath('customer/account/create');
            } catch (Exception $e) {
                $this->logger->critical($e->getMessage());
            }
        }
        return $process();
    }
}
