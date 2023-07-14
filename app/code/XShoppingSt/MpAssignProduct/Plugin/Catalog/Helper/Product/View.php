<?php
namespace XShoppingSt\MpAssignProduct\Plugin\Catalog\Helper\Product;

use Magento\Framework\View\Result\Page as ResultPage;

class View
{
    /**
     * @var \XShoppingSt\MpAssignProduct\Helper\Data
     */
    protected $helper;

    /**
     * @param \XShoppingSt\MpAssignProduct\Helper\Data $helper
     */
    public function __construct(
        \XShoppingSt\MpAssignProduct\Helper\Data $helper
    ) {
        $this->helper = $helper;
    }

    /**
     * Plugin for prepareAndRender
     *
     * @param \Magento\Catalog\Helper\Product\View $subject
     * @param ResultPage $resultPage
     * @param $productId
     * @param $controller
     * @param $params
     */
    public function beforePrepareAndRender(
        \Magento\Catalog\Helper\Product\View $subject,
        ResultPage $resultPage,
        $productId,
        $controller,
        $params = null
    ) {
        $newproductId = $this->helper->getMinimumPriceProducts($productId);
        if ($newproductId) {
            $productId = $newproductId;
        }
        return [$resultPage, $productId, $controller, $params];
    }
}
