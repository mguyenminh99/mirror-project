<?php
/**
 * Mpx Software.
 *
 * @category  Mpx
 * @package   Mpx_Marketplace
 * @author    Mpx
 */

namespace Mpx\Marketplace\Plugin\Product;

use Exception;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Message\ManagerInterface;
use Magento\Store\Model\StoreManager;
use Webkul\Marketplace\Controller\Product\Save;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Mpx\Common\Helper\CommonFunc as MpxValidator;
use Psr\Log\LoggerInterface;
use Mpx\Marketplace\Helper\Constant;
use Mpx\Common\Helper\Constant as CommonConstant;

/**
 * Mpx Marketplace validate custom rules
 */
class BeforeSaveProduct
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
     * @var StoreManager
     */
    private $storeManager;

    /**
     * @param ManagerInterface $messageManager
     * @param RedirectFactory $redirectFactory
     * @param DataPersistorInterface $dataPersistor
     * @param MpxValidator $mpxValidator
     * @param LoggerInterface $logger
     * @param StoreManager $storeManager
     */
    public function __construct(
        ManagerInterface       $messageManager,
        RedirectFactory        $redirectFactory,
        DataPersistorInterface $dataPersistor,
        MpxValidator           $mpxValidator,
        LoggerInterface        $logger,
        StoreManager $storeManager
    ) {
        $this->messageManager = $messageManager;
        $this->redirectFactory = $redirectFactory;
        $this->dataPersistor = $dataPersistor;
        $this->mpxValidator = $mpxValidator;
        $this->logger = $logger;
        $this->storeManager = $storeManager;
    }

    /**
     * Function to run to validate decimal.
     *
     * @param Save $subject
     * @param callable $process
     * @return Redirect
     */
    public function aroundExecute(
        Save $subject,
        callable $process
    ): Redirect {
        //Define Default flag
        $productId = $subject->getRequest()->getParam('product_id');
        $wholeData = $subject->getRequest()->getParams();

        //Call up validate rule here
        $this->validatePrice($wholeData);
        if (empty($this->error)) { //If no price error -> process validate special price and period.
            $this->validateSpecialPricePeriod($wholeData);
        }
        $this->validateDateTime($wholeData);
        $this->validateShortDescription($wholeData);
        $this->validateSku($wholeData);
        $this->validateProductCategory($wholeData);
        //End validate rule call

        if (!empty($this->errors)) {
            try {
                $this->processAddErrorMessage($this->errors);
                $this->dataPersistor->set('seller_catalog_product', $wholeData);
                $this->cleanErrors();
            } catch (Exception $e) {
                $this->messageManager
                    ->addErrorMessage(__('Something went wrong while updating the product(s) attributes.!'));
                $this->logger->critical($e->getMessage());
            }

            if (!$productId) {
                return $this->redirectFactory->create()->setPath(
                    '*/*/add',
                    [
                        'set' => $wholeData['set'],
                        'type' => $wholeData['type'],
                        '_secure' => $subject->getRequest()->isSecure()
                    ]
                );
            } else {
                return $this->redirectFactory->create()->setPath(
                    '*/*/edit',
                    [
                        'id' => $productId,
                        '_secure' => $subject->getRequest()->isSecure(),
                    ]
                );
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
     * @param array $wholeData
     * @return void
     */
    protected function validatePrice(array $wholeData): void
    {
        $priceFlagError = false;
        $price = $wholeData['product']['price'];
        if (!is_numeric($price)) {
            $priceFlagError = true;
        }
        if ($this->mpxValidator->isDecimal($price)) {
            $priceFlagError = true;
        }

        if ($priceFlagError) {
            $this->errors[] = [
                'type' => CommonConstant::PRICE_DECIMAL_ERROR_CODE,
                'message' => CommonConstant::PRICE_DECIMAL_ERROR_MESSAGE
            ];
        }

        //If special price isset or input, validate it, else skip
        if (isset($wholeData['product']['special_price']) &&
            !empty($wholeData['product']['special_price'])) {
            $specialFlagError = false;
            $specialPrice = $wholeData['product']['special_price'];
            if (!is_numeric($specialPrice)) {
                $specialFlagError = true;
            }
            if ($this->mpxValidator->isDecimal($specialPrice)) {
                $specialFlagError = true;
            }
            if ($specialFlagError) {
                $this->errors[] = [
                    'type' => Constant::INVALID_SPECIAL_PRICE_ERROR_CODE,
                    'message' => Constant::INVALID_SPECIAL_PRICE_ERROR_MESSAGE
                ];
            }
        }
    }

    /**
     * Validate special from, to time input
     *
     * @param array $wholeData
     * @return void
     */
    protected function validateDateTime(array $wholeData): void
    {
        $specialFromDate = '';
        $specialToDate = '';
        if (isset($wholeData['product']) && isset($wholeData['product']['special_from_date'])) {
            $specialFromDate = $wholeData['product']['special_from_date'];
        }
        if (isset($wholeData['product']) && isset($wholeData['product']['special_to_date'])) {
            $specialToDate = $wholeData['product']['special_to_date'];
        }
        if (!empty($specialFromDate) && !empty($specialToDate)) {
            $isValidFromDate = $this->mpxValidator
                ->validateTimeFormat($specialFromDate, Constant::JAPANESE_LOCALE_TIME_FORMAT);
            $isValidToDate = $this->mpxValidator
                ->validateTimeFormat($specialToDate, Constant::JAPANESE_LOCALE_TIME_FORMAT);
            if (!$isValidFromDate || !$isValidToDate) {
                $this->errors[] = [
                    'type' => Constant::DATE_VALIDATION_ERROR_CODE,
                    'message' => Constant::DATE_VALIDATION_ERROR_MESSAGE
                ];
            }
        }
    }

    /**
     * Validate Sku
     *
     * @param array $wholeData
     * @return void
     */
    protected function validateSku(array $wholeData): void
    {
        if (isset($wholeData['product']['sku'])) {
            $sku = $wholeData['product']['sku'];
            if (mb_strlen($sku) > Constant::SKU_MAX_LENGTH) {
                $this->errors[] = [
                    'type' => Constant::SKU_LENGTH_ERROR_CODE,
                    'message' => Constant::SKU_LENGTH_ERROR_MESSAGE
                ];
            }
        }
    }

    /**
     * Validate ShortDescription
     *
     * @param array $wholeData
     * @return void
     */
    protected function validateShortDescription(array $wholeData): void
    {
        if (isset($wholeData['product']['short_description'])) {
            $shortDescription = $wholeData['product']['short_description'];
            if (strlen($shortDescription) > Constant::SHORT_DESCRIPTION_MAX_LENGTH) {
                $this->errors[] = [
                    'type' => Constant::SHORT_DESCRIPTION_LENGTH_ERROR_CODE,
                    'message' => Constant::SHORT_DESCRIPTION_LENGTH_ERROR_MESSAGE
                ];
            }
        }
    }

    /**
     * Validate Special price period function
     *
     * RULE:
     * No special price start date entered-> "Enter special price start date"
     * No special price end date entered-> "Enter special price end date"
     * No special price entered-> "Please enter a special price"
     * If the special price period input value is not in date format
     * -> "The date entered is incorrect"
     * If the input value of the special price is not a numerical value
     * -> "Please enter the special price as a numerical value"
     *
     * @param array $wholeData
     * @return void
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    protected function validateSpecialPricePeriod(array $wholeData): void
    {
        $specialFromDate = '';
        $specialToDate = '';
        $specialPrice = '';
        //if 3 inputs is set, process to validate it (mostly simple product only)
        if (isset($wholeData['product']['special_from_date']) &&
            isset($wholeData['product']['special_to_date']) &&
            isset($wholeData['product']['special_price'])
        ) {
            $specialFromDate = $wholeData['product']['special_from_date'];
            $specialToDate = $wholeData['product']['special_to_date'];
            $specialPrice = $wholeData['product']['special_price'];

            //If one of specialFromDate, $specialToDate or $specialPrice has data, the rest also required.
            if ($specialPrice || $specialFromDate || $specialToDate) {
                if (empty($specialPrice)) { //If special price not set, raise error
                    $this->errors[] = [
                        'type' => Constant::EMPTY_SPECIAL_PRICE_CODE,
                        'message' => Constant::EMPTY_SPECIAL_PRICE_MESSAGE
                    ];
                }
                if (empty($specialFromDate)) { //If special from date not set, raise error
                    $this->errors[] = [
                        'type' => Constant::EMPTY_SPECIAL_FROM_CODE,
                        'message' => Constant::EMPTY_SPECIAL_FROM_MESSAGE
                    ];
                }
                if (empty($specialToDate)) { //If special to date not set, raise error
                    $this->errors[] = [
                        'type' => Constant::EMPTY_SPECIAL_TO_CODE,
                        'message' => Constant::EMPTY_SPECIAL_TO_MESSAGE
                    ];
                }
            }
        }
    }

    /**
     * Validate Product Category
     *
     * @param array $wholeData
     * @return void
     */
    protected function validateProductCategory(array $wholeData): void
    {
        try {
            $defaultCategoryId = $this->storeManager->getStore()->getRootCategoryId();
            if (!isset($wholeData['product']['category_ids']) ||
                (count($wholeData['product']['category_ids']) == Constant::MINIMUM_QUANTITY_CATEGORY &&
                    $wholeData['product']['category_ids'][0] == $defaultCategoryId)) {
                $this->errors[] = [
                    'type' => Constant::REQUIRED_CATEGORY_ERROR_CODE,
                    'message' => Constant::REQUIRED_CATEGORY_ERROR_MESSAGE
                ];
            }
        } catch (\Exception $exception) {
            $this->messageManager->addError("Can't get default category.");
        }
    }
}
