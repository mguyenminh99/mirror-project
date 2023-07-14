<?php
namespace XShoppingSt\Marketplace\Api\Data;

interface ProductFlagsSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get ProductFlags list.
     * @return \XShoppingSt\Marketplace\Api\Data\ProductFlagsInterface[]
     */
    public function getItems();

    /**
     * Set ProductFlags list.
     * @param \XShoppingSt\Marketplace\Api\Data\ProductFlagsInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
