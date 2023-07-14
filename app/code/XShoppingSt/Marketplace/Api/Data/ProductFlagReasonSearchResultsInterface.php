<?php
namespace XShoppingSt\Marketplace\Api\Data;

interface ProductFlagReasonSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get ProductFlag list.
     * @return \XShoppingSt\Marketplace\Api\Data\ProductFlagReasonInterface[]
     */
    public function getItems();

    /**
     * Set ProductFlag list.
     * @param \XShoppingSt\Marketplace\Api\Data\ProductFlagReasonInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
