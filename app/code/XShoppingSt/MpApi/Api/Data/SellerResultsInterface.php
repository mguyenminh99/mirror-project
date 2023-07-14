<?php
namespace XShoppingSt\MpApi\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface for preorder Complete search results.
 * @api
 */
interface SellerResultsInterface extends SearchResultsInterface
{
    /**
     * Get sellerlist Complete list.
     *
     * @return \XShoppingSt\Marketplace\Api\Data\SellerInterface[]
     */
    public function getItems();

    /**
     * Set sellerlist Complete list.
     *
     * @param \XShoppingSt\Marketplace\Api\Data\SellerInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
