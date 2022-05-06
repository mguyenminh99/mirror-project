<?php
/**
 * Mpx Software.
 *
 * @category  Mpx
 * @package   Mpx_Marketplace
 * @author    Mpx
 */

namespace Mpx\Marketplace\Plugin\Product;

use Webkul\Marketplace\Controller\Product\Save;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\App\Request\DataPersistorInterface;

/**
 * Mpx Marketplace validate decimal.
 */
class ValidateDecimal
{
    /**
     * @var \Magento\Framework\Message\ManagerInterface
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
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param RedirectFactory $redirectFactory
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\Controller\Result\RedirectFactory $redirectFactory,
        DataPersistorInterface $dataPersistor
    ) {
        $this->messageManager = $messageManager;
        $this->redirectFactory = $redirectFactory;
        $this->dataPersistor = $dataPersistor;
    }

    /**
     * Function to run to validate decimal.
     *
     * @param Save $subject
     * @param callable $process
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function aroundExecute(
        Save $subject,
        callable $process
    ) {
        $errorFlag = false;
        $productId = $subject->getRequest()->getParam('id');
        $wholedata = $subject->getRequest()->getParams();
        $price = $wholedata['product']['price'];
        $specialPrice = $wholedata['product']['special_price'];

        if (!is_numeric($price) && !is_numeric($specialPrice)) {
            return $process();
        }

        if ($this->isDecimal($price)) {
            $errorFlag = true;
        }

        if ($this->isDecimal($specialPrice)) {
            $errorFlag = true;
        }

        if ($errorFlag) {
            $this->messageManager->addErrorMessage(__("Please enter a valid integer in this field."));
            $this->dataPersistor->set('seller_catalog_product', $wholedata);
            if (!$productId) {
                return $this->redirectFactory->create()->setPath(
                    '*/*/add',
                    [
                        'set' => $wholedata['set'],
                        'type' => $wholedata['type'],
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
     * Check if number is decimal
     *
     * @param string $val
     * @return bool
     */
    public function isDecimal(string $val): bool
    {
        return is_numeric($val) && floor($val) != $val;
    }
}
