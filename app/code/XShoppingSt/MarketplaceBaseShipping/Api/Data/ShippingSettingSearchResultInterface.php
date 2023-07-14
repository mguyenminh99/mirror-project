<?php
namespace XShoppingSt\MarketplaceBaseShipping\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * @api
 */
interface ShippingSettingSearchResultInterface extends SearchResultsInterface
{
    /**
     * @return \XShoppingSt\MarketplaceBaseShipping\Api\Data\ShippingSettingInterface[]
     */
    public function getItems();

    /**
     * @param \XShoppingSt\MarketplaceBaseShipping\Api\Data\ShippingSettingInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
