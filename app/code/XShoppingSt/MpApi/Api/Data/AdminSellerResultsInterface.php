<?php
namespace XShoppingSt\MpApi\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface for preorder Complete search results.
 * @api
 */
interface AdminSellerResultsInterface extends SearchResultsInterface
{
    /**
     * Get sellerlist Complete list on search.
     *
     * @return \XShoppingSt\Marketplace\Api\Data\SellerInterface[]
     */
    public function getItems();

    /**
     * Set sellerlist Complete list on search.
     *
     * @param \XShoppingSt\Marketplace\Api\Data\SellerInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
