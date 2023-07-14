<?php
namespace XShoppingSt\Marketplace\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface SellerFlagsRepositoryInterface
{
    /**
     * Save SellerFlags
     * @param \XShoppingSt\Marketplace\Api\Data\SellerFlagsInterface $sellerFlags
     * @return \XShoppingSt\Marketplace\Api\Data\SellerFlagsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \XShoppingSt\Marketplace\Api\Data\SellerFlagsInterface $sellerFlags
    );

    /**
     * Retrieve SellerFlags
     * @param int $entityId
     * @return \XShoppingSt\Marketplace\Api\Data\SellerFlagsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($entityId);

    /**
     * Retrieve SellerFlags matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \XShoppingSt\Marketplace\Api\Data\SellerFlagsSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete SellerFlags
     * @param \XShoppingSt\Marketplace\Api\Data\SellerFlagsInterface $sellerFlags
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \XShoppingSt\Marketplace\Api\Data\SellerFlagsInterface $sellerFlags
    );

    /**
     * Delete SellerFlags by ID
     * @param string $entityId
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($entityId);
}
