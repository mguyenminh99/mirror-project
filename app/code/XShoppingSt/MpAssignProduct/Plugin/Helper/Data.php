<?php
namespace XShoppingSt\MpAssignProduct\Plugin\Helper;

class Data
{
    /**
     * @var \XShoppingSt\MpAssignProduct\Model\ItemsFactory
     */
    protected $assignedItem;

    /**
     * @param \XShoppingSt\MpAssignProduct\Model\ItemsFactory $assignedItem
     */
    public function __construct(
        \XShoppingSt\MpAssignProduct\Model\ItemsFactory $assignedItem
    ) {
        $this->assignedItem = $assignedItem;
    }

    /**
     * Plugin for getSellerProductDataByProductId
     *
     * @param \XShoppingSt\Marketplace\Helper\Data $subject
     * @param \Closure $proceed
     * @param $productId
     * @return $result
     */
    public function aroundGetSellerProductDataByProductId(
        \XShoppingSt\Marketplace\Helper\Data $subject,
        \Closure $proceed,
        $productId
    ) {
        $collecton = $proceed($productId);
        if ($collecton->getSize()) {
            return $collecton;
        }
        $assignItem = $this->assignedItem->create()->getCollection();
        $assignItem->addFieldToFilter('assign_product_id', $productId);
        return $assignItem;
    }

    /**
     * Plugin for getSellerIdByProductId
     *
     * @param \XShoppingSt\Marketplace\Helper\Data $subject
     * @param \Closure $proceed
     * @param $productId
     * @return $result
     */
    public function aroundGetSellerIdByProductId(
        \XShoppingSt\Marketplace\Helper\Data $subject,
        \Closure $proceed,
        $productId
    ) {
        $sellerId = $proceed($productId);
        if ($sellerId) {
            return $sellerId;
        }
        $assignItem = $this->assignedItem->create()->getCollection();
        $assignItem->addFieldToFilter('assign_product_id', $productId);
        return $assignItem->getFirstItem()->getSellerId();
    }
}
