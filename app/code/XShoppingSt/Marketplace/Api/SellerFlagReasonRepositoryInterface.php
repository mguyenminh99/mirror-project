<?php
namespace XShoppingSt\Marketplace\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface SellerFlagReasonRepositoryInterface
{
    /**
     * Save SellerFlagReason
     * @param \XShoppingSt\Marketplace\Api\Data\SellerFlagReasonInterface $sellerFlagReason
     * @return \XShoppingSt\Marketplace\Api\Data\SellerFlagReasonInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \XShoppingSt\Marketplace\Api\Data\SellerFlagReasonInterface $sellerFlagReason
    );

    /**
     * Retrieve SellerFlagReason
     * @param int $entityId
     * @return \XShoppingSt\Marketplace\Api\Data\SellerFlagReasonInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($entityId);

    /**
     * Retrieve SellerFlagReason matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \XShoppingSt\Marketplace\Api\Data\SellerFlagReasonSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete SellerFlagReason
     * @param \XShoppingSt\Marketplace\Api\Data\SellerFlagReasonInterface $sellerFlagReason
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \XShoppingSt\Marketplace\Api\Data\SellerFlagReasonInterface $sellerFlagReason
    );

    /**
     * Delete SellerFlagReason by ID
     * @param string $entityId
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($entityId);
}
