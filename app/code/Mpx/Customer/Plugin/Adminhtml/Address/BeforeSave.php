<?php
namespace Mpx\Customer\Plugin\Adminhtml\Address;

use Magento\Framework\Controller\Result\JsonFactory;
use Mpx\Customer\Helper\PostcodeValidation;
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
     * @var PostcodeValidation
     */
    protected $postcodeValidation;

    /**
     * @param LoggerInterface $logger
     * @param PostcodeValidation $postcodeValidation
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
        LoggerInterface        $logger,
        PostcodeValidation     $postcodeValidation,
        JsonFactory            $resultJsonFactory
    ) {
        $this->logger = $logger;
        $this->postcodeValidation = $postcodeValidation;
        $this->resultJsonFactory = $resultJsonFactory;
    }

    /**
     * Function to run to validate post code.
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
        $error = $this->postcodeValidation->validatePostCode($params);
        $subject->getRequest()->setParam('postcode', str_replace("-", "", $postCode));

        if (!empty($error)) {
            try {
                $this->postcodeValidation->setErrorMessageToMessageManager($error);
                $resultJson->setData(
                    [
                        'messages' => __($error[0]['message']),
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
