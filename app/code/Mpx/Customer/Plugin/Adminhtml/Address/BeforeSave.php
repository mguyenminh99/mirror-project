<?php
namespace Mpx\Customer\Plugin\Adminhtml\Address;

use Magento\Framework\Controller\Result\JsonFactory;
use Mpx\Customer\Helper\CustomerInfoValidation;
use Psr\Log\LoggerInterface;
use Exception;
use Magento\Framework\Controller\Result\Json;

class BeforeSave
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var CustomerInfoValidation
     */
    protected $customerInfoValidation;

    /**
     * @param LoggerInterface $logger
     * @param CustomerInfoValidation $customerInfoValidation
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
        LoggerInterface            $logger,
        CustomerInfoValidation     $customerInfoValidation,
        JsonFactory                $resultJsonFactory
    ) {
        $this->logger = $logger;
        $this->customerInfoValidation = $customerInfoValidation;
        $this->resultJsonFactory = $resultJsonFactory;
    }

    /**
     * Function to run to validate customer information.
     *
     * @param \Magento\Customer\Controller\Adminhtml\Address\Save $subject
     * @param callable $process
     * @return Json
     */
    public function aroundExecute(
        \Magento\Customer\Controller\Adminhtml\Address\Save $subject,
        callable $process
    ): Json {
        $resultJson = $this->resultJsonFactory->create();
        $params = $subject->getRequest()->getParams();
        $postCode = $params['postcode'];
        $errors = [];
        $errors = $this->customerInfoValidation->validatePostCode($params);
        $errors = $this->customerInfoValidation->validatePhoneNumber($params);
        $subject->getRequest()->setParam('postcode', str_replace("-", "", $postCode));

        if (!empty($errors)) {
            try {
                $messages =[];
                foreach ($errors as $error) {
                    $messages[] = __($error['message']);
                }
                $resultJson->setData(
                    [
                        'messages' => $messages,
                        'error' => true,
                        'data' => [
                            'postcode' => $postCode
                        ]
                    ]
                );
                return $resultJson;
            } catch (Exception $e) {
                $this->logger->critical($e->getMessage());
            }
        }
        return $process();
    }
}
