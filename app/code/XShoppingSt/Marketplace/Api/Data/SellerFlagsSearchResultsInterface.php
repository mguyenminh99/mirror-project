<?php
namespace XShoppingSt\Marketplace\Api\Data;

interface SellerFlagsSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get SellerFlags list.
     * @return \XShoppingSt\Marketplace\Api\Data\SellerFlagsInterface[]
     */
    public function getItems();

    /**
     * Set SellerFlags list.
     * @param \XShoppingSt\Marketplace\Api\Data\SellerFlagsInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
