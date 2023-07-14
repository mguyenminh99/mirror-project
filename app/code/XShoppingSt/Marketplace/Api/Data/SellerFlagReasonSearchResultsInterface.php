<?php
namespace XShoppingSt\Marketplace\Api\Data;

interface SellerFlagReasonSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get SellerFlag list.
     * @return \XShoppingSt\Marketplace\Api\Data\SellerFlagReasonInterface[]
     */
    public function getItems();

    /**
     * Set SellerFlag list.
     * @param \XShoppingSt\Marketplace\Api\Data\SellerFlagReasonInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
