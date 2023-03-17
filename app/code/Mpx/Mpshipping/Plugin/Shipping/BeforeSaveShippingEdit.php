<?php
/**
 * Mpx Software.
 *
 * @category  Mpx
 * @package   Mpx_Mpshipping
 * @author    Mpx
 */

namespace Mpx\Mpshipping\Plugin\Shipping;

use Exception;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Message\ManagerInterface;
use Webkul\Mpshipping\Controller\Shipping\Edit;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Mpx\Marketplace\Helper\CommonFunc as MpxValidator;
use Psr\Log\LoggerInterface;
use Mpx\Marketplace\Helper\Constant;

/**
 * Mpx Marketplace validate custom rules
 */
class BeforeSaveShippingEdit
{

    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * @var RedirectFactory
     */
    protected $redirectFactory;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var MpxValidator
     */
    protected $mpxValidator;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Array of errors that caught during validate
     * Define by 2 dimensional array type and message
     * Sample
     * [
     *      type => 'price_decimal',
     *      message => "Price can not be decimal!"
     * ]
     *
     * @var array
     */
    protected $errors = [];

    /**
     * @param ManagerInterface $messageManager
     * @param RedirectFactory $redirectFactory
     * @param DataPersistorInterface $dataPersistor
     * @param MpxValidator $mpxValidator
     * @param LoggerInterface $logger
     */
    public function __construct(
        ManagerInterface       $messageManager,
        RedirectFactory        $redirectFactory,
        DataPersistorInterface $dataPersistor,
        MpxValidator           $mpxValidator,
        LoggerInterface        $logger
    ) {
        $this->messageManager = $messageManager;
        $this->redirectFactory = $redirectFactory;
        $this->dataPersistor = $dataPersistor;
        $this->mpxValidator = $mpxValidator;
        $this->logger = $logger;
    }

    /**
     * Function to run to validate decimal.
     *
     * @param Edit $subject
     * @param callable $process
     * @return Redirect
     */
    public function aroundExecute(
        Edit $subject,
        callable $process
    ): Redirect {
        $params = $subject->getRequest()->getParams();

        //Call up validate rule here
        $this->validatePrice($params);

        //End validate rule call

        if (!empty($this->errors)) {
            try {
                $this->processAddErrorMessage($this->errors);
                $this->cleanErrors();
                return $this->redirectFactory->create()->setPath('mpshipping/shipping/view');
            } catch (Exception $e) {
                $this->messageManager
                    ->addErrorMessage(__('Something went wrong while updating the product(s) attributes.!'));
                $this->logger->critical($e->getMessage());
            }

        }

        return $process();
    }

    /**
     * Add all errors message founds
     *
     * @param array $errors
     * @return void
     */
    protected function processAddErrorMessage(array $errors): void
    {
        if (!empty($errors)) {
            foreach ($errors as $error) {
                if (isset($error['message'])) {
                    $this->messageManager
                        ->addErrorMessage(__($error['message']));
                } else {
                    $this->messageManager
                        ->addErrorMessage(__('Error in validation process!'));
                }
            }
        }
    }

    /**
     * Clean up error(s)
     *
     * @return void
     */
    protected function cleanErrors(): void
    {
        if (!empty($this->errors)) {
            $this->errors[] = [];
        }
    }

    /**
     * Validate product price
     *
     * @param array $params
     * @return void
     */
    protected function validatePrice(array $params): void
    {
        $price = $params['price'];

        if (isset($price)) {

            if (!is_numeric($price)) {
                $this->errors[] = [
                    'type' => Constant::PRICE_NONE_NUMERIC_ERROR_CODE,
                    'message' => Constant::PRICE_NONE_NUMERIC_ERROR_MESSAGE
                ];
            }

            if ($this->mpxValidator->isDecimal($price)) {
                $this->errors[] = [
                    'type' => Constant::PRICE_DECIMAL_ERROR_CODE,
                    'message' => Constant::PRICE_DECIMAL_ERROR_MESSAGE
                ];
            }
        }
    }
}
